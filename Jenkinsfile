pipeline {
  agent any
  stages {

    stage('init') {
      parallel {
        stage('debug') {
          steps {
            sh './.jenkins/bin/init/debug.sh'
          }
        }
        stage('append files to project') {
          steps {
            sh './.jenkins/bin/init/append_files.sh'
          }
        }
        stage('composer install') {
          steps {
            sh './.jenkins/bin/init/composer_install.sh'
          }
        }
      }
    }

    stage('unattended_install') {
      parallel {
        stage('debug') {
          steps {
            sh './.jenkins/bin/unattended_install/default_env.sh'
          }
        }
      }
    }

    stage('test') {
      parallel {
        stage('phpunit') {
          steps {
            sh './.jenkins/bin/tests/phpunit.sh'
          }
        }
      }
    }

    stage('archive phpunit result') {
      parallel {
        stage('archive phpunit result') {
          steps {
            junit 'var/test/phpunit-log.junit.xml'
          }
        }
      }
    }
    stage('notify') {
      steps {
        slackSend(color: '#00FF00', message: "Build finished (${currentBuild.result}), Job '${env.JOB_NAME} [${env.BUILD_NUMBER}]' (${env.BUILD_URL})")
      }
    }

  }
  environment {
    DEBUG_UNIT_TEST = '0'
  }
  options {
    timeout(time: 20, unit: 'MINUTES')
  }
}