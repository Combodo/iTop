#/bin/bash

#git diff --name-status 2.6.2..HEAD js |grep   'A\sjs/' |awk -F/ '{printf("lib/%s/%s\n",$2,$3)}'|sort |uniq >/tmp/toto
#git diff --name-status 2.6.2..HEAD lib |grep   'A\slib/' |awk -F/ '{printf("lib/%s/%s\n",$2,$3)}'|sort |uniq >/tmp/toto

function HELP(){
  echo "    Syntax: bash $0 /var/www/html/iTop"
}

if [ $# -eq 0 ]
then
	echo "no iTop path provided"
  HELP
	exit 1
fi

iTopPath=$1

if [ ! -d $iTopPath ]
then
	echo "$iTopPath is not an iTop path."
  HELP
	exit 1
fi

echo "<?xml version=\"1.0\"?>
<licenses>"

for subfolder in lib datamodels
do
  for l in $(find $iTopPath/$subfolder/ -name composer.json|sed 's|/composer.json||')
  do
    if [ ! -d $l ]
    then
      continue
    fi
    if [ "$subfolder" == "datamodels" -a $(find $l -name module*.php|wc -l) -ne 0 ]
    then
      continue
    fi
    dir=$(dirname $(dirname $l))
    prod=$(echo $l| sed "s|$dir/||1")
    echo $l $subfolder
    lictype=$(cd $l && composer licenses --format json |jq .license[] |sed 's|\"||g')

    authors=""
    if [ -f $l/composer.json ]
    then
      author_nb=$(grep -c authors $l/composer.json|sed 's| ||g')
      if [ "x$author_nb" != "x0" ]
      then
        OLDIFS=$IFS
        IFS=$'\n'
        for a in $(cat $l/composer.json |jq .authors[].name|sed 's|\"||g')
        do
          authors="$authors$a - "
        done
        authors="$authors#"
        authors=$(echo $authors |sed 's| - #||')
        IFS=$OLDIFS
      fi
    fi

    lic=""
    for licf in $(find $l -name LICEN*)
    do
      lic=$(cat $licf)
      break
    done

    #if [ "x$lic" == "x" ]
    #then
    #	echo "============== no license found $l"
    #fi

    echo "    <license>
          <product scope=\"$subfolder\">$prod</product>
          <author>$authors</author>
          <license_type>$lictype</license_type>
          <text><![CDATA[
  $lic
  ]]></text>
      </license>"
  done
done

echo "</licenses>"