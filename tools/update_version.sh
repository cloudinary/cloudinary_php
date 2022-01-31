#!/usr/bin/env bash

# Update version number and prepare for publishing the new version

set -e

# Empty to run the rest of the line and "echo" for a dry run
CMD_PREFIX=

# Add a quote if this is a dry run
QUOTE=

NEW_VERSION=

UPDATE_ONLY=false

function echo_err
{
    echo "$@" 1>&2;
}

function usage
{
      echo "Usage: $0 [parameters]"
      echo "      -v | --version <version>"
      echo "      -d | --dry-run print the commands without executing them"
      echo "      -u | --update-only only update the version"
      echo "      -h | --help print this information and exit"
      echo
      echo "For example: $0 -v 1.2.3"
}

function process_arguments
{
    while [[ "$1" != "" ]]; do
        case $1 in
            -v | --version )
                shift
                NEW_VERSION=${1:-}
                if ! [[ "${NEW_VERSION}" =~ [0-9]+\.[0-9]+\.[0-9]+(\-.+)? ]]; then
                  echo_err "You must supply a new version after -v or --version"
                  echo_err "For example:"
                  echo_err "  1.2.3"
                  echo_err "  1.2.3-rc1"
                  echo_err ""
                  usage; return 1
                fi
                ;;
            -d | --dry-run )
                CMD_PREFIX=echo
                echo "Dry Run"
                echo ""
                ;;
            -u | --update-only )
                UPDATE_ONLY=true
                echo "Only update version"
                echo ""
                ;;
            -h | --help )
                usage; return 0
                ;;
            * )
              usage; return 1
        esac
        shift || true
    done
}

# Intentionally make pushd silent
function pushd
{
    command pushd "$@" > /dev/null
}

# Intentionally make popd silent
function popd
{
    command popd > /dev/null
}

# Check if one version is less than or equal than other
# Example:
# ver_lte 1.2.3 1.2.3 && echo "yes" || echo "no" # yes
# ver_lte 1.2.3 1.2.4 && echo "yes" || echo "no" # yes
# ver_lte 1.2.4 1.2.3 && echo "yes" || echo "no" # no
function ver_lte
{
    [[ "$1" = "`echo -e "$1\n$2" | sort -V | head -n1`" ]]
}

# Extract the last entry or entry for a given version
# The function is not currently used in this file.
# Examples:
#   changelog_last_entry
#   changelog_last_entry 1.10.0
#
function changelog_last_entry
{
    sed -e "1,/^${1}/d" -e '/^=/d' -e '/^$/d' -e '/^[0-9]/,$d' CHANGELOG.md
}

function verify_dependencies
{
    # Test if the gnu grep is installed
    if ! grep --version | grep -q GNU
    then
        echo_err "GNU grep is required for this script"
        echo_err "You can install it using the following command:"
        echo_err ""
        echo_err "brew install grep --with-default-names"
        return 1
    fi

    if [[ "${UPDATE_ONLY}" = true ]]; then
      return 0;
    fi

    if [[ -z "$(type -t git-changelog)" ]]
    then
        echo_err "git-extras packages is not installed."
        echo_err "You can install it using the following command:"
        echo_err ""
        echo_err "brew install git-extras"
        return 1
    fi
}

# Replace old string only if it is present in the file, otherwise return 1
function safe_replace
{
    local old=$1
    local new=$2
    local file=$3

    grep -q "${old}" "${file}" || { echo_err "${old} was not found in ${file}"; return 1; }

    ${CMD_PREFIX} sed -i.bak -e "${QUOTE}s/${old}/${new}/${QUOTE}" -- "${file}"  && rm -- "${file}.bak"
}

function update_version
{
    if [[ -z "${NEW_VERSION}" ]]; then
        usage; return 1
    fi

    # Enter git root
    pushd $(git rev-parse --show-toplevel)

    local current_version=`grep -oiP '(?<="version": ")([a-zA-Z0-9\-.]+)(?=")' composer.json`

    if [[ -z "${current_version}" ]]; then
        echo_err "Failed getting current version, please check directory structure and/or contact developer"
        return 1
    fi

    # Use literal dot character in regular expression
    local current_version_re=${current_version//./\\.}

    echo "# Current version is: ${current_version}"
    echo "# New version is:     ${NEW_VERSION}"

    ver_lte "${NEW_VERSION}" "${current_version}" && { echo_err "New version is not greater than current version"; return 1; }

    # Add a quote if this is a dry run
    QUOTE=${CMD_PREFIX:+"'"}

    safe_replace "\"version\": \"${current_version_re}\""\
                 "\"version\": \"${NEW_VERSION}\""\
                  composer.json\
                  || return 1
    safe_replace "const VERSION = '${current_version_re}'"\
                 "const VERSION = '${NEW_VERSION}'"\
                  src/Cloudinary.php\
                  || return 1

    safe_replace "'version'              => '${current_version_re}'"\
                 "'version'              => '${NEW_VERSION}'"\
                  docs/sami_config.php\
                  || return 1

    if [[ "${UPDATE_ONLY}" = true ]]; then
      popd;
      return 0;
    fi

    ${CMD_PREFIX} git changelog -t ${NEW_VERSION} || true

    echo ""
    echo "# After editing CHANGELOG.md, optionally review changes and issue these commands:"
    echo git add composer.json src/Cloudinary.php CHANGELOG.md docs/sami_config.php
    echo git commit -m "\"Version ${NEW_VERSION}\""
    echo sed -e "'1,/^${NEW_VERSION//./\\.}/d'" \
             -e "'/^=/d'" \
             -e "'/^$/d'" \
             -e "'/^[0-9]/,\$d'" \
             CHANGELOG.md \
         \| git tag -a "'${NEW_VERSION}'" --file=-

    # Don't run those commands on dry run
    [[ -n "${CMD_PREFIX}" ]] && { popd; return 0; }

    echo ""
    read -p "Run the above commands automatically? (y/N): " confirm && [[ ${confirm} == [yY] || ${confirm} == [yY][eE][sS] ]] || { popd; return 0; }

    git add composer.json src/Cloudinary.php CHANGELOG.md docs/sami_config.php
    git commit -m "Version ${NEW_VERSION}"
    sed -e "1,/^${NEW_VERSION//./\\.}/d" \
        -e "/^=/d" \
        -e "/^$/d" \
        -e "/^[0-9]/,\$d" \
        CHANGELOG.md \
    | git tag -a "${NEW_VERSION}" --file=-

    popd
}

process_arguments "$@"
verify_dependencies
update_version
