/**
 * TODO: This function should be embedded in the framework itself
 * A canonical Color formatter, Supports the following inputs:
 * {name:color, is_required:true qualifiers: [{group:'color', name: 'red', is_required:true}]}
 * {group:'color', name: 'red', is_required:true}
 * {group:'color', name: 'rgb', qualifiers: [{ "name": "color", "value": "#d5d2ca", "is_required": true, "value_type": "string" }]}
 * @param payload
 * @constructor
 */
function CanonicalColorQualifier(payload) {
    const {qualifierDTO, langConfig} = payload;
    // This case supports two types of qualifiers
    // TODO this structure needs to be aligned


    if (qualifierDTO.qualifiers && qualifierDTO.name === 'rgb') {
        return [
            payload.formatClassOrEnum(qualifierDTO.group, langConfig),
            langConfig.groupDelimiter,
            payload.formatMethod(qualifierDTO.name, langConfig),
            '(',
            `'${qualifierDTO.qualifiers[0].value}'`.replace('#', ''),
            ')'
        ].join('');
    } else {
        const simpleColorName = qualifierDTO.qualifiers ? qualifierDTO.qualifiers[0].name : qualifierDTO.name;
        return [
            payload.formatClassOrEnum(qualifierDTO.group, langConfig),
            langConfig.groupDelimiter,
            simpleColorName.toUpperCase()
        ].join('');
    }
}


module.exports = {
    "SDKSpecVersion": {},
    "langConfig": {
        newInstanceSyntax: '(new #name(#req))#optional',
        lang: 'PHP',
        methodDelimiter: '->',
        groupDelimiter: '::',
        openQualifiersChar: '',
        closeQualifiersChar: '',
        closeTransformationChar: '',
        unsupportedTxParams: [],
        mainTransformationString: {
            openSyntaxString: {
                image: '(new ImageTag(\'#publicID\'))',
                video: '(new VideoTag(\'#publicID\'))',
                media: '(new MediaTag(\'#publicID\'))'
            },
            closeSyntaxString: ';'
        },
        openActionChar: '(',
        closeActionChar: ')',
        hideActionGroups: false,
        overwritePreset: 'php',
        arraySeparator: ', ',
        arrayOpen: '[',
        arrayClose: ']',
        formats: {
            formatMethod: 'camelCase',
            formatClassOrEnum: 'PascalCase',
            formatFloat: (f) => {
                if (!f.toString().includes('.')) {
                    return `${f}.0` // In JS world, 1.0 is 1, so we make sure 1.0 stays 1.0
                } else {
                    return f;
                }
            }
        },
        methodNameMap: {
            'signature': 'sign',
            'url_suffix': 'suffix'
        },
        classNameMap: {},
        childTransformations: {
            image: {
                open: "(new ImageTransformation())", // TODO Seems like we should reuse the newInstanceSyntax
                close: '',
            },
            video: {
                open: "(new VideoTransformation())",// TODO Seems like we should reuse the newInstanceSyntax
                close: '',
            },
            media: {
                open: "(new MediaTransformation())",// TODO Seems like we should reuse the newInstanceSyntax
                close: '',
            }
        },
    },
    "overwrites": {
        "qualifiers": {
            color_override: (payload) => {
                const {qualifierDTO, langConfig} = payload;
                const colorName = qualifierDTO.qualifiers[0].name;
                const group = qualifierDTO.qualifiers[0].group;

                // TODO this should be streamlined with how we de. al with color.
                return `->colorOverride(Color::${colorName.toUpperCase()})`
            },
            color: CanonicalColorQualifier // TODO this functionality should be embeded in the framework itself
        }
    }
}
