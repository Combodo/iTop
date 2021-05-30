.. Copyright (C) 2010-2021 Combodo SARL
.. http://opensource.org/licenses/AGPL-3.0

.. _Panel:

Panel
=====

Class Panel

----

.. include:: /manual/Component/Panel/PanelAdditionalDescription.rst

----

Twig Tag
--------

:Tag: **UIPanel**

:Syntax:

.. code-block:: twig

    {% UIPanel Type {Parameters} %}
        Content Goes Here
    {% EndUIPanel %}

:Type:

+---------------------------------------------------------------------+---------------------------------------------------------------+
| :ref:`Neutral <PanelNeutral>`                                       | Make a basis Panel component                                  |
+---------------------------------------------------------------------+---------------------------------------------------------------+
| :ref:`ForInformation <PanelForInformation>`                         | Make a Panel component for informational messages             |
+---------------------------------------------------------------------+---------------------------------------------------------------+
| :ref:`ForSuccess <PanelForSuccess>`                                 | Make a Panel component for successful messages                |
+---------------------------------------------------------------------+---------------------------------------------------------------+
| :ref:`ForWarning <PanelForWarning>`                                 | Make a Panel component for warning messages                   |
+---------------------------------------------------------------------+---------------------------------------------------------------+
| :ref:`ForDanger <PanelForDanger>`                                   | Make a Panel component for danger messages                    |
+---------------------------------------------------------------------+---------------------------------------------------------------+
| :ref:`ForFailure <PanelForFailure>`                                 | Make a Panel component for failure messages                   |
+---------------------------------------------------------------------+---------------------------------------------------------------+
| :ref:`WithBrandingPrimaryColor <PanelWithBrandingPrimaryColor>`     | Make a Panel component with primary color scheme              |
+---------------------------------------------------------------------+---------------------------------------------------------------+
| :ref:`WithBrandingSecondaryColor <PanelWithBrandingSecondaryColor>` | Make a Panel component with secondary color scheme            |
+---------------------------------------------------------------------+---------------------------------------------------------------+
| :ref:`ForClass <PanelForClass>`                                     | Make a Panel component with the specific $sClass color scheme |
+---------------------------------------------------------------------+---------------------------------------------------------------+

.. _PanelNeutral:

Panel Neutral
^^^^^^^^^^^^^

:syntax:

.. code-block:: twig

    {% UIPanel Neutral {sTitle:'value', sSubTitle:'value'} %}
        Content Goes Here
    {% EndUIPanel %}

:parameters:

+-----------+--------+-----------+------+--+
| sTitle    | string | mandatory |      |  |
+-----------+--------+-----------+------+--+
| sSubTitle | string | optional  | NULL |  |
+-----------+--------+-----------+------+--+

.. _PanelForInformation:

Panel ForInformation
^^^^^^^^^^^^^^^^^^^^

:syntax:

.. code-block:: twig

    {% UIPanel ForInformation {sTitle:'value', sSubTitle:'value'} %}
        Content Goes Here
    {% EndUIPanel %}

:parameters:

+-----------+--------+-----------+------+--+
| sTitle    | string | mandatory |      |  |
+-----------+--------+-----------+------+--+
| sSubTitle | string | optional  | NULL |  |
+-----------+--------+-----------+------+--+

.. _PanelForSuccess:

Panel ForSuccess
^^^^^^^^^^^^^^^^

:syntax:

.. code-block:: twig

    {% UIPanel ForSuccess {sTitle:'value', sSubTitle:'value'} %}
        Content Goes Here
    {% EndUIPanel %}

:parameters:

+-----------+--------+-----------+------+--+
| sTitle    | string | mandatory |      |  |
+-----------+--------+-----------+------+--+
| sSubTitle | string | optional  | NULL |  |
+-----------+--------+-----------+------+--+

.. _PanelForWarning:

Panel ForWarning
^^^^^^^^^^^^^^^^

:syntax:

.. code-block:: twig

    {% UIPanel ForWarning {sTitle:'value', sSubTitle:'value'} %}
        Content Goes Here
    {% EndUIPanel %}

:parameters:

+-----------+--------+-----------+------+--+
| sTitle    | string | mandatory |      |  |
+-----------+--------+-----------+------+--+
| sSubTitle | string | optional  | NULL |  |
+-----------+--------+-----------+------+--+

.. _PanelForDanger:

Panel ForDanger
^^^^^^^^^^^^^^^

:syntax:

.. code-block:: twig

    {% UIPanel ForDanger {sTitle:'value', sSubTitle:'value'} %}
        Content Goes Here
    {% EndUIPanel %}

:parameters:

+-----------+--------+-----------+------+--+
| sTitle    | string | mandatory |      |  |
+-----------+--------+-----------+------+--+
| sSubTitle | string | optional  | NULL |  |
+-----------+--------+-----------+------+--+

.. _PanelForFailure:

Panel ForFailure
^^^^^^^^^^^^^^^^

:syntax:

.. code-block:: twig

    {% UIPanel ForFailure {sTitle:'value', sSubTitle:'value'} %}
        Content Goes Here
    {% EndUIPanel %}

:parameters:

+-----------+--------+-----------+------+--+
| sTitle    | string | mandatory |      |  |
+-----------+--------+-----------+------+--+
| sSubTitle | string | optional  | NULL |  |
+-----------+--------+-----------+------+--+

.. _PanelWithBrandingPrimaryColor:

Panel WithBrandingPrimaryColor
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

:syntax:

.. code-block:: twig

    {% UIPanel WithBrandingPrimaryColor {sTitle:'value', sSubTitle:'value'} %}
        Content Goes Here
    {% EndUIPanel %}

:parameters:

+-----------+--------+-----------+------+--+
| sTitle    | string | mandatory |      |  |
+-----------+--------+-----------+------+--+
| sSubTitle | string | optional  | NULL |  |
+-----------+--------+-----------+------+--+

.. _PanelWithBrandingSecondaryColor:

Panel WithBrandingSecondaryColor
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

:syntax:

.. code-block:: twig

    {% UIPanel WithBrandingSecondaryColor {sTitle:'value', sSubTitle:'value'} %}
        Content Goes Here
    {% EndUIPanel %}

:parameters:

+-----------+--------+-----------+------+--+
| sTitle    | string | mandatory |      |  |
+-----------+--------+-----------+------+--+
| sSubTitle | string | optional  | NULL |  |
+-----------+--------+-----------+------+--+

.. _PanelForClass:

Panel ForClass
^^^^^^^^^^^^^^

:syntax:

.. code-block:: twig

    {% UIPanel ForClass {sClass:'value', sTitle:'value', sSubTitle:'value'} %}
        Content Goes Here
    {% EndUIPanel %}

:parameters:

+-----------+--------+-----------+------+--------------------------------------+
| sClass    | string | mandatory |      | Class of the object the panel is for |
+-----------+--------+-----------+------+--------------------------------------+
| sTitle    | string | mandatory |      |                                      |
+-----------+--------+-----------+------+--------------------------------------+
| sSubTitle | string | optional  | NULL |                                      |
+-----------+--------+-----------+------+--------------------------------------+

Panel common parameters
^^^^^^^^^^^^^^^^^^^^^^^
+-------------------------+-----------------+------------------------------------------------------------+
| AddCSSClass             | string          | CSS class to add to the generated html block               |
+-------------------------+-----------------+------------------------------------------------------------+
| AddCSSClasses           | array           | like <code>['ibo-is-hidden', 'ibo-alert--body']</code>     |
+-------------------------+-----------------+------------------------------------------------------------+
| AddCssFileRelPath       | string          | relative path of a CSS file to add                         |
+-------------------------+-----------------+------------------------------------------------------------+
| AddDeferredBlock        | iUIBlock        |                                                            |
+-------------------------+-----------------+------------------------------------------------------------+
| AddHtml                 | string          |                                                            |
+-------------------------+-----------------+------------------------------------------------------------+
| AddJsFileRelPath        | string          | relative path of a JS file to add                          |
+-------------------------+-----------------+------------------------------------------------------------+
| AddMainBlock            | iUIBlock        |                                                            |
+-------------------------+-----------------+------------------------------------------------------------+
| AddMainBlocks           | array           |                                                            |
+-------------------------+-----------------+------------------------------------------------------------+
| AddSubBlock             | iUIBlock        | directly in the main area                                  |
+-------------------------+-----------------+------------------------------------------------------------+
| AddSubTitleBlock        | iUIBlock        |                                                            |
+-------------------------+-----------------+------------------------------------------------------------+
| AddSubTitleBlocks       | array           |                                                            |
+-------------------------+-----------------+------------------------------------------------------------+
| AddTitleBlock           | iUIBlock        |                                                            |
+-------------------------+-----------------+------------------------------------------------------------+
| AddTitleBlocks          | array           |                                                            |
+-------------------------+-----------------+------------------------------------------------------------+
| AddToolbarBlock         | iUIBlock        |                                                            |
+-------------------------+-----------------+------------------------------------------------------------+
| AddToolbarBlocks        | array           |                                                            |
+-------------------------+-----------------+------------------------------------------------------------+
| CSSClasses              | array           | like <code>['ibo-is-hidden', 'ibo-alert--body']</code>     |
+-------------------------+-----------------+------------------------------------------------------------+
| Color                   | string          |                                                            |
+-------------------------+-----------------+------------------------------------------------------------+
| ColorFromClass          | string          |                                                            |
+-------------------------+-----------------+------------------------------------------------------------+
| ColorFromOrmStyle       | ormStyle        |                                                            |
+-------------------------+-----------------+------------------------------------------------------------+
| DataAttributes          | array           | Array of data attributes in the format ['name' => 'value'] |
+-------------------------+-----------------+------------------------------------------------------------+
| DeferredBlocks          | array           |                                                            |
+-------------------------+-----------------+------------------------------------------------------------+
| IsCollapsible           | bool            |                                                            |
+-------------------------+-----------------+------------------------------------------------------------+
| IsHeaderVisibleOnScroll | bool            |                                                            |
+-------------------------+-----------------+------------------------------------------------------------+
| IsHidden                | bool            | Indicates if the block is hidden by default                |
+-------------------------+-----------------+------------------------------------------------------------+
| MainBlocks              | array           |                                                            |
+-------------------------+-----------------+------------------------------------------------------------+
| SubBlocks               | array           |                                                            |
+-------------------------+-----------------+------------------------------------------------------------+
| SubTitle                | string          |                                                            |
+-------------------------+-----------------+------------------------------------------------------------+
| SubTitleBlock           | iUIContentBlock |                                                            |
+-------------------------+-----------------+------------------------------------------------------------+
| Title                   | string          |                                                            |
+-------------------------+-----------------+------------------------------------------------------------+
| TitleBlock              | iUIContentBlock |                                                            |
+-------------------------+-----------------+------------------------------------------------------------+
| ToolBlocks              | array           |                                                            |
+-------------------------+-----------------+------------------------------------------------------------+

----

.. include:: /manual/Component/Panel/PanelFooter.rst
