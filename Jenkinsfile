// ============================================================
// admin.crosschips.com CI/CD（Jenkins Declarative Pipeline）
//
// Job：admin-crosschips-cicd（Pipeline script from SCM，脚本路径 Jenkinsfile）
// 触发：GitHub push Webhook + 手动「立即构建」
//
// 流程：
//   1. Jenkins 用服务器上的 Deploy Key 拉本仓库到工作区
//   2. ci/jenkins-deploy.sh：
//        首次把站点 index/ 备份为 index-bak → rsync 源码 → composer install
//        →（有 .env 时）artisan 发布资源/清缓存/迁移/重建缓存 → 记录 commit
//   3. ci/jenkins-healthcheck.sh：探测源站响应（未就绪只标黄，不阻断部署）
//
// 服务器侧依赖：
//   $JENKINS_HOME/deploy/ssh/id_ed25519_admin   GitHub Deploy Key（仓库只读）
//   Jenkins 凭据 admin-crosschips-deploy-key     引用上面这把私钥
// ============================================================
pipeline {
  agent any

  options {
    timestamps()
    disableConcurrentBuilds()
    buildDiscarder(logRotator(numToKeepStr: '30'))
    timeout(time: 30, unit: 'MINUTES')
  }

  environment {
    APP_NAME      = 'admin-crosschips'
    SITE_HOST     = 'admin.crosschips.com'
    SITE_ROOT     = '/opt/1panel/www/sites/admin.crosschips.com'
    PHP_CONTAINER = 'php85'
  }

  triggers {
    githubPush()
  }

  stages {
    stage('准备') {
      steps {
        sh '''
          set -euo pipefail
          echo "分支: ${BRANCH_NAME:-master}"
          git --no-pager log -1 --pretty='提交: %h  %an  %s'
        '''
      }
    }

    stage('部署到 index/') {
      steps {
        sh 'bash ci/jenkins-deploy.sh'
      }
    }

    stage('站点探测') {
      steps {
        catchError(buildResult: 'SUCCESS', stageResult: 'UNSTABLE') {
          sh 'bash ci/jenkins-healthcheck.sh'
        }
      }
    }
  }

  post {
    success {
      echo "✅ ${env.APP_NAME} 代码部署完成 → ${env.SITE_ROOT}/index"
    }
    unstable {
      echo "🟡 代码已部署，但站点探测未通过（多为 nginx root 未指向 index/public 或 index/.env 缺失）"
    }
    failure {
      echo "❌ 失败：请看上方日志；上一版代码仍在 ${env.SITE_ROOT}/index-bak"
    }
  }
}
