.. Copyright (C) 2010-2021 Combodo SARL
.. http://opensource.org/licenses/AGPL-3.0

Column
======

Class Column

----

.. include:: /manual/Layout/MultiColumn/Column/ColumnAdditionalDescription.rst

----

Twig Tag
--------

:Tag: **UIColumn**

:Syntax:
 
::

    {% UIColumn Type {Parameters} %}
        Content Goes Here
    {% EndUIColumn %}

:Type:

+----------+------------+
| Standard | No comment |
+----------+------------+
| ForBlock | No comment |
+----------+------------+

:Column *Standard* parameters:

+-----+--------+----------+------+--+
| sId | string | optional | NULL |  |
+-----+--------+----------+------+--+

:Column *ForBlock* parameters:

+--------+---------+-----------+------+--+
| oBlock | UIBlock | mandatory |      |  |
+--------+---------+-----------+------+--+
| sId    | string  | optional  | NULL |  |
+--------+---------+-----------+------+--+

:Column common parameters:

+-------------------+----------+------------------------------------------------------------+
| AddCSSClass       | string   | CSS class to add to the generated html block               |
+-------------------+----------+------------------------------------------------------------+
| AddCSSClasses     | array    | like <code>['ibo-is-hidden', 'ibo-alert--body']</code>     |
+-------------------+----------+------------------------------------------------------------+
| AddCssFileRelPath | string   |                                                            |
+-------------------+----------+------------------------------------------------------------+
| AddDeferredBlock  | iUIBlock |                                                            |
+-------------------+----------+------------------------------------------------------------+
| AddHtml           | string   |                                                            |
+-------------------+----------+------------------------------------------------------------+
| AddJsFileRelPath  | string   |                                                            |
+-------------------+----------+------------------------------------------------------------+
| AddSubBlock       | iUIBlock |                                                            |
+-------------------+----------+------------------------------------------------------------+
| CSSClasses        | array    | like <code>['ibo-is-hidden', 'ibo-alert--body']</code>     |
+-------------------+----------+------------------------------------------------------------+
| DataAttributes    | array    | Array of data attributes in the format ['name' => 'value'] |
+-------------------+----------+------------------------------------------------------------+
| DeferredBlocks    | array    |                                                            |
+-------------------+----------+------------------------------------------------------------+
| IsHidden          | bool     | Indicates if the block is hidden by default                |
+-------------------+----------+------------------------------------------------------------+
| SubBlocks         | array    |                                                            |
+-------------------+----------+------------------------------------------------------------+

----

.. include:: /manual/Layout/MultiColumn/Column/ColumnFooter.rst
