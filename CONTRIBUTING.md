Contributing
============

- [Submission Guidelines](#submission-guidelines)
- [Coding Rules](#coding-rules)
- [Commit Message Guidelines](#commit-message-guidelines)

## Submission Guidelines

### Submitting an Issue

## Coding Rules

### Project Structure

Reference https://github.com/symfony/demo

## Commit Message Guidelines

### Commit Message Format

Reference https://www.conventionalcommits.org/en/v1.0.0/

```
<type>: <description>

<body>

<footer>
```

Examples:

```
feat: added file upload endpoints
```
```
fix: large file no longer max cpu usage

Change file sanitisation to use temp file instead of piping data into a new file.
```

### Type

Must be one of the following:
- build: Changes that affect the build system or external dependencies (example scopes: gulp, broccoli, npm)
- ci: Changes to our CI configuration files and scripts (example scopes: Travis, Circle, BrowserStack, SauceLabs)
- docs: Documentation only changes
- feat: A new feature
- fix: A bug fix
- perf: A code change that improves performance
- refactor: A code change that neither fixes a bug nor adds a feature
- style: Changes that do not affect the meaning of the code (white-space, formatting, missing semi-colons, etc)
- test: Adding missing tests or correcting existing tests
- chore:
- build:
- revert:

### Footer

The footer should contain any information about **Breaking Changes**, to reference GitHub issues that this commit Closes,
or commit's revision number being reverted.

**Breaking Changes** should start with the word `BREAKING CHANGE:`. The rest of the commit message is then used for this.

Examples:

```
fix!: updated file sanitization and rules

BREAKING CHANGE: .docx is no longer supported
```
```
revert: use original style

This commit reverts 3c7d02241006e6e6965f5f0ca66fdb4cd345f109
```
