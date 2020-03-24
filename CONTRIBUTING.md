# Contributing to Cloudinary PHP library

Contributions are welcome and greatly appreciated!

## Reporting a bug

- Make sure that the bug was not already reported by searching in GitHub under [Issues](https://github.com/cloudinary/cloudinary_php) and the Cloudinary [Support forms](https://support.cloudinary.com).
- If you're unable to find an open issue addressing the problem, [open a new one](https://github.com/cloudinary/cloudinary_php/issues/new).
  Be sure to include a **title and clear description**, as relevant information as possible, and a **code sample** or an **executable test case** demonstrating the expected behavior that is not occurring.
- If you require assistance in the implementation of cloudinary_php please [submit a request](https://support.cloudinary.com/hc/en-us/requests/new) on Cloudinary's site.

## Requesting a feature

We would love to receive your requests!
Please be aware that the library is used in a wide variety of environments and that some features may not be applicable to all users.

- Open a GitHub [issue](https://github.com/cloudinary/cloudinary_php) describing the benefits (and possible drawbacks) of the requested feature

## Fixing a bug / Implementing a new feature

- Follow the instructions detailed in [Code contribution](#code-contribution)
- Open a new GitHub pull request
- Ensure the PR description clearly describes the bug / feature. Include relevant issue number if applicable.
- Provide test code that covers the new code
- The code should support:
  - PHP >= 5.6

## Code contribution

When contributing code, either to fix a bug or to implement a new feature, please follow these guidelines:

#### Fork the Project

Fork [project on Github](https://github.com/cloudinary/cloudinary_php) and check your copy.

```
git clone https://github.com/contributor/cloudinary_php.git
cd cloudinary_php
git remote add upstream https://github.com/cloudinary/cloudinary_php.git
```

#### Create a Topic Branch

Make sure your fork is up-to-date and create a topic branch for your feature or bug fix.

```
git checkout master
git pull upstream master
git checkout -b my-feature-branch
```
#### Rebase

If you've been working on a change for a while, rebase with upstream/master.

```
git fetch upstream
git rebase upstream/master
git push origin my-feature-branch -f
```


#### Write Tests

Try to write a test that reproduces the problem you're trying to fix or describes a feature you would like to build.

We definitely appreciate pull requests that highlight or reproduce a problem, even without a fix.

#### Write Code

Implement your feature or bug fix.
Follow the following PHP coding standards, described in [PSR-0](http://www.php-fig.org/psr/psr-0/), [PSR-1](http://www.php-fig.org/psr/psr-1/), [PSR-2](http://www.php-fig.org/psr/psr-2/) and [PSR-4](http://www.php-fig.org/psr/psr-4/) documentation.
The code should support:

  - PHP >= 5.6

Make sure that `phpunit` completes without errors.

#### Write Documentation

Document any external behavior in the [README](README.md).

#### Commit Changes

Make sure git knows your name and email address:

```
git config --global user.name "Your Name"
git config --global user.email "contributor@example.com"
```

Writing good commit logs is important. A commit log should describe what changed and why.

```
git add ...
git commit
```


> Please squash your commits into a single commit when appropriate. This simplifies future cherry picks and keeps the git log clean.

#### Push

```
git push origin my-feature-branch
```

#### Make a Pull Request

Go to https://github.com/contributor/cloudinary_php and select your feature branch. Click the 'Pull Request' button and fill out the form. Pull requests are normally reviewed within a few days.
Ensure the PR description clearly describes the problem and solution. Include relevant issue number if applicable.

#### Rebase

If you've been working on a change for a while, rebase with upstream/master.

```
git fetch upstream
git rebase upstream/master
git push origin my-feature-branch -f
```

#### Check on Your Pull Request

Go back to your pull request after a few minutes and see whether it passed muster with Travis-CI. Everything should look green, otherwise - fix issues and amend your commit as described above.

#### Be Patient

It's likely that your change will not be merged and that the nitpicky maintainers will ask you to do more, or fix seemingly benign problems. Hang on there!

#### Thank You

Please do know that we really appreciate and value your time and work. We love you, really.
