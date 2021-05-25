.. Copyright (C) 2010-2021 Combodo SARL
.. http://opensource.org/licenses/AGPL-3.0
.. _FieldSet:

FieldSet
========

*In Progress*

----

Output Result
-------------

:Example FieldSets:

.. image:: FieldSet.png

----

Twig Tag
--------

:Tag: **UIFieldSet**

:Syntax:

::

    {% UIFieldSet Type {Parameters} %}
        Content Goes Here
    {% EndUIFieldSet %}

:Type:

+------------------------------+-----------------------------------------------------+
| *Standard*                   | Create a *Default FieldSet*                         |
+------------------------------+-----------------------------------------------------+
| *Small*                      | Create a *FieldSet* with *small* layout             |
+------------------------------+-----------------------------------------------------+
| *Large*                      | Create a *FieldSet* with *large* layout             |
+------------------------------+-----------------------------------------------------+
| *FromParams*                 | Create a *FieldSet* from given parameters           |
+------------------------------+-----------------------------------------------------+

:FieldSet common parameters:

+-------------------+--------+-----------+----------------------------------+
| *sLegend*         | string | Mandatory | Displayed legend of the FieldSet |
+-------------------+--------+-----------+----------------------------------+
| *sId*             | string | optional  | ID of the HTML block             |
+-------------------+--------+-----------+----------------------------------+

:See also: :ref:`UIBlock Common parameters <UIBlock_parameters>`

:Related Tag: :ref:`Field <Field>`

----

Examples
--------

Example to generate a FieldSet::

    {% UIFieldSet Standard {sLegend: "iTop environment"} %}
        {% UIField ... %}
        ...
    {% EndUIFieldSet %}

The result:

.. image:: FieldSet-explained.png

