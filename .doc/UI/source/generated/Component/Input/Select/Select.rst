.. Copyright (C) 2010-2022 Combodo SARL
.. http://opensource.org/licenses/AGPL-3.0

.. _Select:

Select
======

Class Select

----

.. include:: /manual/Component/Input/Select/SelectAdditionalDescription.rst

----

Twig Tag
--------

:Tag: **UISelect**

:Syntax:

.. code-block:: twig

    {% UISelect Type {Parameters} %}
        Content Goes Here
    {% EndUISelect %}

:Type:

+------------------------------------------------------+------------------------------------+
| :ref:`ForSelect <SelectForSelect>`                   | Create a default Select input      |
+------------------------------------------------------+------------------------------------+
| :ref:`ForSelectWithLabel <SelectForSelectWithLabel>` | Create a Select input with a label |
+------------------------------------------------------+------------------------------------+

.. _SelectForSelect:

Select ForSelect
^^^^^^^^^^^^^^^^

:syntax:

.. code-block:: twig

    {% UISelect ForSelect {sName:'value', sId:'value'} %}
        Content Goes Here
    {% EndUISelect %}

:parameters:

+-------+--------+-----------+------+-------------------------+
| sName | string | mandatory |      | Input name for the form |
+-------+--------+-----------+------+-------------------------+
| sId   | string | optional  | NULL | ID of the block         |
+-------+--------+-----------+------+-------------------------+

.. _SelectForSelectWithLabel:

Select ForSelectWithLabel
^^^^^^^^^^^^^^^^^^^^^^^^^

:syntax:

.. code-block:: twig

    {% UISelect ForSelectWithLabel {sName:'value', sLabel:'value', sId:'value'} %}
        Content Goes Here
    {% EndUISelect %}

:parameters:

+--------+--------+-----------+------+-----------------------------------------------------+
| sName  | string | mandatory |      | Input name for the form                             |
+--------+--------+-----------+------+-----------------------------------------------------+
| sLabel | string | mandatory |      | Label to display with the input (null for no label) |
+--------+--------+-----------+------+-----------------------------------------------------+
| sId    | string | optional  | NULL | ID of the block                                     |
+--------+--------+-----------+------+-----------------------------------------------------+

Select common parameters
^^^^^^^^^^^^^^^^^^^^^^^^

+-----------------------------+--------------+------------------------------------------------------------+
| AddCSSClass                 | string       | CSS class to add to the generated html block               |
+-----------------------------+--------------+------------------------------------------------------------+
| AddCSSClasses               | array        | like <code>['ibo-is-hidden', 'ibo-alert--body']</code>     |
+-----------------------------+--------------+------------------------------------------------------------+
| AddCssFileRelPath           | string       |                                                            |
+-----------------------------+--------------+------------------------------------------------------------+
| AddDeferredBlock            | iUIBlock     |                                                            |
+-----------------------------+--------------+------------------------------------------------------------+
| AddHtml                     | string       |                                                            |
+-----------------------------+--------------+------------------------------------------------------------+
| AddJsFileRelPath            | string       |                                                            |
+-----------------------------+--------------+------------------------------------------------------------+
| AddMultipleCssFilesRelPaths | array        |                                                            |
+-----------------------------+--------------+------------------------------------------------------------+
| AddMultipleJsFilesRelPaths  | array        |                                                            |
+-----------------------------+--------------+------------------------------------------------------------+
| AddOption                   | SelectOption | Select option UIBlock                                      |
+-----------------------------+--------------+------------------------------------------------------------+
| AddSubBlock                 | iUIBlock     |                                                            |
+-----------------------------+--------------+------------------------------------------------------------+
| CSSClasses                  | array        | like <code>['ibo-is-hidden', 'ibo-alert--body']</code>     |
+-----------------------------+--------------+------------------------------------------------------------+
| DataAttributes              | array        | Array of data attributes in the format ['name' => 'value'] |
+-----------------------------+--------------+------------------------------------------------------------+
| DeferredBlocks              | array        |                                                            |
+-----------------------------+--------------+------------------------------------------------------------+
| Description                 | string       |                                                            |
+-----------------------------+--------------+------------------------------------------------------------+
| HasForcedDiv                | bool         |                                                            |
+-----------------------------+--------------+------------------------------------------------------------+
| IsHidden                    | bool         |                                                            |
+-----------------------------+--------------+------------------------------------------------------------+
| IsLabelBefore               | bool         | If true the label will be positioned before the input      |
+-----------------------------+--------------+------------------------------------------------------------+
| IsMultiple                  | bool         | Allow multiple selection                                   |
+-----------------------------+--------------+------------------------------------------------------------+
| Label                       | string       | Label to display with the input (null for no label)        |
+-----------------------------+--------------+------------------------------------------------------------+
| Name                        | string       | Input name for the form                                    |
+-----------------------------+--------------+------------------------------------------------------------+
| SubBlocks                   | array        |                                                            |
+-----------------------------+--------------+------------------------------------------------------------+
| SubmitOnChange              | bool         | if true submit the form as soon as a change is detected    |
+-----------------------------+--------------+------------------------------------------------------------+

----

.. include:: /manual/Component/Input/Select/SelectFooter.rst
