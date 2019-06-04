# Phpdoc dokuwiki template
This directory contains a template rendering iTop phpdoc as wiki pages.


conventional tag that you should use:
 * `@internal` : exclude from the documentation.
 * `@api` : it means that a method is an api, thus it may be interacted with.
 * `@see` : it points to another documented method
 * `@link` : external url 
   * if you point to another page of the wiki, please use relative links.
 * `@example` : let you provide example of code
 * `@param`, `@return`, `@throws`, ... 


## Special instructions

some tags where added : 
 * `@api-advanced`: it means that a method is an `@api` but mark it also as "complex" to use
 * `@overwritable-hook`: used to mark a method as "designed to be extended"
 * `@extension-hook`: not used for now 
 * `@phpdoc-tuning-exclude-inherited`: once this tag is present on a class, it's inherited methods won't be showed.

 
### known limitations:
#### `@see` tags must be very specific: 
   * always prefix class members with `ClassName::` 
   * for methods always suffix them with `()`, 
   * do not reference variables since they are not documented. If you have to, always prefix them with `$`
 
examples: 
```
/** 
 * @see DBObject
 * @see DBObject::Get()
 * @see DBObject::$foo
 */
```   

#### Do not use inline tags, they do not work properly, example: 
```
/** 
 * This is a texts with ans inline tag {@see [FQSEN] [<description>]} it must never be used 
 */
```
   
#### The `@example` tag must respect this very precise syntax 
 * the sentence in the first line (next to the tag) is the title, it  must be enclose by double quotes 
 * the following lines are the sample code. 
 * 💔 since we simply hack the official tag, this syntax must be respected carefully 💔
example: 
```
/** 
* @example "This is the title of the multiline example"
* $foo = DBObject::Get('foo');
* DBObject::Set('foo', ++$foo);
*/
```  
    
## How content is included into the documentation

**For a class** those requirements have to be respected: 
 - the file containing the class must be listed in `/phpdoc/files/file[]` of  `.doc/phpdoc-objects-manipulation.dist.xml`
 - the class **must not** have the tag `@internal`
 - the class **must** have at least one of: `@api`, `@api-advanced`, `@overwritable-hook`, `@extension-hook` 

Then, **for a method** of an eligible class: 
 - **public** methods **must** have at least one of: `@api`, `@api-advanced`, `@overwritable-hook`, `@extension-hook` 
 - **protected** methods **must** have at least one of: `@overwritable-hook`, `@extension-hook` 
 - **private** methods are **always excluded** 

**Class properties** and **constants** are never documented (this is subject to change).




## A note about the rendering engine
  
:notebook: as spaces are used to mark code, the templates (`.doc/phpdoc-templates/combodo-wiki/*`) have very few indentation, thus they are awful to read (sorry).




## Installation
```
composer require phpdocumentor/phpdocumentor:~2 --dev
```

## Generation
`.doc/bin/build-doc-object-manipulation` and `.doc/bin/build-doc-extensions` contains examples of doc. generation, beware: they have to be called from iTop root directory:
```shell
cd /path/to/itop/
./.doc/bin/build-doc-object-manipulation
``` 

the resulting documentation is written into `data/phpdocumentor/output`


## Dokuwiki requirements
 * the template uses the [wrap plugin](https://www.dokuwiki.org/plugin:wrap).
 * the generated files have to be placed under an arbitrary directory of `[/path/to/dokuwiki]/data/pages`.
 * the html has to be activated [config:htmlok](https://www.dokuwiki.org/config:htmlok)
 * the generated files have to be in lowercase
 
