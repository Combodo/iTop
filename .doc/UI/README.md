# Twig based UI blocks documentation

This documentation explains how to use UI blocks (new in 3.0) in twig based extensions.

# How to build UI Documentation

## Install sphinx

### Windows users

Install Python from https://www.python.org/downloads/windows/

Under `.doc/UI`, run

```
pip install -U sphinx
pip install sphinx-rtd-theme
pip install recommonmark
pip install sphinx-multiversion
```

### Linux users

You should have Python installed.

Under `.doc/UI`, run

```
sudo apt-get install python3-sphinx
pip install sphinx-rtd-theme
pip install recommonmark
pip install sphinx-multiversion
```

## Generating the documentation

### Generating the documentation source from the actual code

Under `.doc/UI`

```
php bin/generate_uiblock.php
```

When the source have not changed, just regenerate the documentation using the following methods.

### Regenerating the doc

#### Windows users

Under `.doc/UI`
```
make.bat html
```

The documentation is generated into `.doc/UI/build/html`

#### Linux users

Under `.doc/UI`, run
```
make html
```

The documentation is generated into `.doc/UI/build/html`
