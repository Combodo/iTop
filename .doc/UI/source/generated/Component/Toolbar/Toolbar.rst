.. Copyright (C) 2010-2021 Combodo SARL
.. http://opensource.org/licenses/AGPL-3.0

Toolbar
=======

Class Toolbar

----

.. include:: /manual/Component/Toolbar/ToolbarAdditionalDescription.rst

----

Twig Tag
--------

:Tag: **UIToolbar**

:Syntax:
 
::

    {% UIToolbar Type {Parameters} %}
        Content Goes Here
    {% EndUIToolbar %}

:Type:

+-----------+------------+
| ForAction | No comment |
+-----------+------------+
| Standard  | No comment |
+-----------+------------+
| ForButton | No comment |
+-----------+------------+

:Toolbar *ForAction* parameters:

+-----+--------+----------+------+--+
| sId | string | optional | NULL |  |
+-----+--------+----------+------+--+

:Toolbar *Standard* parameters:

+-------------------+--------+----------+----------+--+
| sId               | string | optional | NULL     |  |
+-------------------+--------+----------+----------+--+
| aContainerClasses | array  | optional | array () |  |
+-------------------+--------+----------+----------+--+

:Toolbar *ForButton* parameters:

+-------------------+--------+----------+----------+--+
| sId               | string | optional | NULL     |  |
+-------------------+--------+----------+----------+--+
| aContainerClasses | array  | optional | array () |  |
+-------------------+--------+----------+----------+--+

:Toolbar common parameters:

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

.. include:: /manual/Component/Toolbar/ToolbarFooter.rst
