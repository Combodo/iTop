# How to build UI Documentation

## Sphinx

Install Sphinx from https://www.sphinx-doc.org/en/master/usage/installation.html

## ReadTheDoc template

The documentation template comes from https://readthedocs.org/ and is already in the sources

## Generating the documentation

### Windows users
Under `.doc/UI` simply run `make.bat html`

The documentation is generated into `.doc/UI/build/html`


### Linux users

#### setup
Under `.doc/UI`, run 
```
sudo apt-get install python3-sphinx
pip install sphinx-rtd-theme
pip install recommonmark
```

#### doc generation

Under `.doc/UI`, run
```
make html
```