.. Copyright (C) 2010-2021 Combodo SARL
.. http://opensource.org/licenses/AGPL-3.0

.. _MultiColumn:

MultiColumn
===========

Class MultiColumn

----

.. include:: /manual/Layout/MultiColumn/MultiColumnAdditionalDescription.rst

----

Twig Tag
--------

:Tag: **UIMultiColumn**

:Syntax:
 
::

    {% UIMultiColumn Type {Parameters} %}
        Content Goes Here
    {% EndUIMultiColumn %}

:Type:

+---------------------------------------+------------+
| :ref:`Standard <MultiColumnStandard>` | No comment |
+---------------------------------------+------------+

.. _MultiColumnStandard:

MultiColumn Standard
^^^^^^^^^^^^^^^^^^^^

:syntax:

::

    {% UIMultiColumn Standard {sId:'value'} %}
        Content Goes Here
    {% EndUIMultiColumn %}

:parameters:

+-----+--------+----------+------+--+
| sId | string | optional | NULL |  |
+-----+--------+----------+------+--+

MultiColumn common parameters
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
+-------------------+----------+------------------------------------------------------------+
| AddCSSClass       | string   | CSS class to add to the generated html block               |
+-------------------+----------+------------------------------------------------------------+
| AddCSSClasses     | array    | like <code>['ibo-is-hidden', 'ibo-alert--body']</code>     |
+-------------------+----------+------------------------------------------------------------+
| AddColumn         | Column   |                                                            |
+-------------------+----------+------------------------------------------------------------+
| AddCssFileRelPath | string   | relative path of a CSS file to add                         |
+-------------------+----------+------------------------------------------------------------+
| AddDeferredBlock  | iUIBlock |                                                            |
+-------------------+----------+------------------------------------------------------------+
| AddHtml           | string   |                                                            |
+-------------------+----------+------------------------------------------------------------+
| AddJsFileRelPath  | string   | relative path of a JS file to add                          |
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

.. include:: /manual/Layout/MultiColumn/MultiColumnFooter.rst
