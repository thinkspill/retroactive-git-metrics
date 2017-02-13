# retroactive-git-metrics

Runs `sonar-scanner` on each historical commit of a git repo. Only analyzes the first commit of each day as SonarQube does not allow specifying the commit time, thereby restricting retroactive commits to one per day.
