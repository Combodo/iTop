def infra

node(){
    properties([
            buildDiscarder(
                    logRotator(
                            daysToKeepStr: "28",
                            numToKeepStr: "500")
            )
    ])

  checkout scm

  infra = load '/var/lib/jenkins/workspace/itop-test-infra_6644-phpstan/src/Infra.groovy'
}


infra.call()

