.. Copyright (C) 2010-2021 Combodo SARL
.. http://opensource.org/licenses/AGPL-3.0

CollapsibleSection
==================

*In Progress*

----

Output Result
-------------

:Example CollapsibleSections:


----

Twig Tag
--------

:Tag: **UICollapsibleSection**

:Syntax:

::

    {% UICollapsibleSection Type {Parameters} %}
        Content Goes Here
    {% EndUICollapsibleSection %}

:Type:

+------------------------------+-----------------------------------------------------+
| *Standard*                   | Create a *Default CollapsibleSection*               |
+------------------------------+-----------------------------------------------------+

:CollapsibleSection common parameters:

+-------------------+--------+-----------+----------------------------------+
| *sId*             | string | optional  | ID of the HTML block             |
+-------------------+--------+-----------+----------------------------------+

:See also: :ref:`UIBlock Common parameters <UIBlock_parameters>`

----

Examples
--------

Example to generate a CollapsibleSection::

    {% UICollapsibleSection Type {Parameters} %}
        Content Goes Here
    {% EndUICollapsibleSection %}

