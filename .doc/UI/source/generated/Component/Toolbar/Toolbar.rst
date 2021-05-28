.. Copyright (C) 2010-2021 Combodo SARL
.. http://opensource.org/licenses/AGPL-3.0

.. _Toolbar:

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

+-------------------------------------+------------+
| :ref:`ForAction <ToolbarForAction>` | No comment |
+-------------------------------------+------------+
| :ref:`Standard <ToolbarStandard>`   | No comment |
+-------------------------------------+------------+
| :ref:`ForButton <ToolbarForButton>` | No comment |
+-------------------------------------+------------+

.. _ToolbarForAction:

Toolbar ForAction
^^^^^^^^^^^^^^^^^

:syntax:

::

    {% UIToolbar ForAction {sId:'value'} %}
        Content Goes Here
    {% EndUIToolbar %}

:parameters:

+-----+--------+----------+------+--+
| sId | string | optional | NULL |  |
+-----+--------+----------+------+--+

.. _ToolbarStandard:

Toolbar Standard
^^^^^^^^^^^^^^^^

:syntax:

::

    {% UIToolbar Standard {sId:'value', aContainerClasses:{name:value, name:value}} %}
        Content Goes Here
    {% EndUIToolbar %}

:parameters:

+-------------------+--------+----------+----------+--+
| sId               | string | optional | NULL     |  |
+-------------------+--------+----------+----------+--+
| aContainerClasses | array  | optional | array () |  |
+-------------------+--------+----------+----------+--+

.. _ToolbarForButton:

Toolbar ForButton
^^^^^^^^^^^^^^^^^

:syntax:

::

    {% UIToolbar ForButton {sId:'value', aContainerClasses:{name:value, name:value}} %}
        Content Goes Here
    {% EndUIToolbar %}

:parameters:

+-------------------+--------+----------+----------+--+
| sId               | string | optional | NULL     |  |
+-------------------+--------+----------+----------+--+
| aContainerClasses | array  | optional | array () |  |
+-------------------+--------+----------+----------+--+

Toolbar common parameters
^^^^^^^^^^^^^^^^^^^^^^^^^
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
