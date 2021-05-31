.. Copyright (C) 2010-2021 Combodo SARL
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

+------------------------------------------------------+------------------------------------------------------------------------------------------------+
| :ref:`ForSelect <SelectForSelect>`                   | @param string $sName                                                                           |
+------------------------------------------------------+------------------------------------------------------------------------------------------------+
| :ref:`ForSelectWithLabel <SelectForSelectWithLabel>` | If you need to have a real field with a label, you might use a {@link Field} component instead |
+------------------------------------------------------+------------------------------------------------------------------------------------------------+

.. _SelectForSelect:

Select ForSelect
^^^^^^^^^^^^^^^^

:syntax:

.. code-block:: twig

    {% UISelect ForSelect {sName:'value', sId:'value'} %}
        Content Goes Here
    {% EndUISelect %}

:parameters:

+-------+--------+-----------+------+--+
| sName | string | mandatory |      |  |
+-------+--------+-----------+------+--+
| sId   | string | optional  | NULL |  |
+-------+--------+-----------+------+--+

.. _SelectForSelectWithLabel:

Select ForSelectWithLabel
^^^^^^^^^^^^^^^^^^^^^^^^^

:syntax:

.. code-block:: twig

    {% UISelect ForSelectWithLabel {sName:'value', sLabel:'value', sId:'value'} %}
        Content Goes Here
    {% EndUISelect %}

:parameters:

+--------+--------+-----------+------+--+
| sName  | string | mandatory |      |  |
+--------+--------+-----------+------+--+
| sLabel | string | mandatory |      |  |
+--------+--------+-----------+------+--+
| sId    | string | optional  | NULL |  |
+--------+--------+-----------+------+--+

Select common parameters
^^^^^^^^^^^^^^^^^^^^^^^^
+-------------------+--------------+------------------------------------------------------------+
| AddCSSClass       | string       | CSS class to add to the generated html block               |
+-------------------+--------------+------------------------------------------------------------+
| AddCSSClasses     | array        | like <code>['ibo-is-hidden', 'ibo-alert--body']</code>     |
+-------------------+--------------+------------------------------------------------------------+
| AddCssFileRelPath | string       |                                                            |
+-------------------+--------------+------------------------------------------------------------+
| AddDeferredBlock  | iUIBlock     |                                                            |
+-------------------+--------------+------------------------------------------------------------+
| AddHtml           | string       |                                                            |
+-------------------+--------------+------------------------------------------------------------+
| AddJsFileRelPath  | string       |                                                            |
+-------------------+--------------+------------------------------------------------------------+
| AddOption         | SelectOption |                                                            |
+-------------------+--------------+------------------------------------------------------------+
| AddSubBlock       | iUIBlock     |                                                            |
+-------------------+--------------+------------------------------------------------------------+
| CSSClasses        | array        | like <code>['ibo-is-hidden', 'ibo-alert--body']</code>     |
+-------------------+--------------+------------------------------------------------------------+
| DataAttributes    | array        | Array of data attributes in the format ['name' => 'value'] |
+-------------------+--------------+------------------------------------------------------------+
| DeferredBlocks    | array        |                                                            |
+-------------------+--------------+------------------------------------------------------------+
| IsHidden          | bool         | Indicates if the block is hidden by default                |
+-------------------+--------------+------------------------------------------------------------+
| IsMultiple        | bool         |                                                            |
+-------------------+--------------+------------------------------------------------------------+
| Name              | string       |                                                            |
+-------------------+--------------+------------------------------------------------------------+
| SubBlocks         | array        |                                                            |
+-------------------+--------------+------------------------------------------------------------+
| SubmitOnChange    | bool         |                                                            |
+-------------------+--------------+------------------------------------------------------------+
| Value             | string       |                                                            |
+-------------------+--------------+------------------------------------------------------------+

----

.. include:: /manual/Component/Input/Select/SelectFooter.rst
