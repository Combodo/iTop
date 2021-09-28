# How to build UI Documentation

## Generating the documentation

### Windows users

#### setup

Install Python from https://www.python.org/downloads/windows/

```
pip install -U sphinx
pip install sphinx-rtd-theme
pip install recommonmark
```

#### doc generation

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

The documentation is generated into `.doc/UI/build/html`
