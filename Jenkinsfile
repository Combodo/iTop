def infra

node(){
  checkout scm

  infra = load '/var/lib/jenkins/workspace/itop-test-infra_master/src/Infra.groovy'
}


infra.call()

