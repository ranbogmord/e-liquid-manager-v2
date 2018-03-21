node {
    def app

    stage("Clone repo") {
        checkout scm
    }

    stage("Install dependencies") {
        sh "composer install --ignore-platform-reqs"
        sh "npm install"
        sh "npm run dev"
    }

    stage("Build image") {
        app = docker.build("ranbogmord/elm")
    }

    stage("Upload image to repository") {
        docker.withRegistry("https://registry.hub.docker.com", "docker-hub-creds") {
            app.push("${env.BUILD_NUMBER}")
            app.push("latest")
        }
    }

    stage("Deploy") {
        withCredentials([sshUserPrivateKey(credentialsId: 'docker-host-ssh', keyFileVariable: 'SSH_KEY_PATH', passphraseVariable: '', usernameVariable: 'SSH_USERNAME')]) {
            sh "ssh -i $SSH_KEY_PATH $SSH_USERNAME@docker-host.ranbogmord.com 'cd elm && docker-compose pull app && docker-compose up -d app'"
        }
    }
}