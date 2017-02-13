  <?php
require_once 'vendor/autoload.php';
$src = __DIR__;
$tmp = sys_get_temp_dir() . '/git-history' . random_int(1111, 9999);
shell_exec("cp -r $src $tmp");
$git = new \SebastianBergmann\Git\Git($tmp);
$revs = $git->getRevisions();
$commits = [];
foreach ($revs as $rev) {
    $indexDate = $rev['date']->format(DateTime::ATOM);
    $commits[$indexDate] = $rev['sha1'];
}
ksort($commits);
$lastDate = false;
foreach ($commits as $date => $hash) {
    $date = substr($date,0,10);
    $git->checkout($hash);
    echo "====================================================================== Scanning $hash";
    $scanCommand = "cd $tmp \\
&& mkdir -p tests \\
&& sonar-scanner \\
    -Dsonar.projectVersion=$hash \\
    -Dsonar.host.url=http://localhost:9001 \\
    -Dsonar.projectKey=phoenix-history \\
    -Dsonar.projectName=phoenix-history \\
    -Dsonar.projectDate=$date \\
    -Dsonar.sources=app \\
    -Dsonar.tests=tests
";
    if ($date !== $lastDate) {
        passthru($scanCommand);
    }
    $lastDate = $date;
}
