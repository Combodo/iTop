def infra

node(){
  checkout scm

  infra = load '/var/lib/jenkins/workspace/itop-test-multitesting/src/Infra.groovy'
}


infra.call()

