Changements principaux:
- la classe AbstractObject est sortie du biz model
- join_type remplacé par is_null_allowed (placé à la fin pour être + facile à retrouver)
- j'ai enlevé toute la classe logLocatedObject qui était en commentaire
- Enlevé 'address' de l'advanced search sur une location car ce n'est plus un critère de recherche possible (remplacé par country)
- Ajouté des critères de recherche sur bizCircuit
- Ajouté les ZList sur bizCircuit
- Ajouté les Zlist pour bizInterface
- Ajouté les Zlist pour lnkInfraInfra
- Ajouté les Zlist pour lnkInfraTicket

Dans AbstractObject: désactivé l'affichage des contacts liés qui ne marche pas pour les tickets.

Bug fix ?
- J'ai rajouté un blindage if (is_object($proposedValue) &&... dans AttributeDate::MakeRealValue mais je ne comprends pas d'où sort la classe DateTime... et pourtant il y en a...

Améliorations:
- Ajouter une vérification des ZList (les attributs/critèresde recherche déclarés dans la liste existent-ils pour cet objet)

Ne marche pas:
- Objets avec des clefs externes vides
- Enums !!!!

Data Generator:
Organization '1' updated.
5 Location objects created.
19 PC objects created.
19 Network Device objects created.
42 Person objects created.
6 Incident objects created.
17 Infra Group objects created.
34 Infra Infra objects created.
