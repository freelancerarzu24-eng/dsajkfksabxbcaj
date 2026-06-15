<?php
require_once __DIR__ . '/auth.php';
checkAdmin();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
validateCSRF();

$db = new Database();
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_guide'])) {
    $id = (int)$_POST['id'];
    $match_id = (int)$_POST['match_id'];
    $country = trim($_POST['country']);
    $broadcaster_id = (int)$_POST['broadcaster_id'];
    $ott_id = (int)$_POST['ott_id'];
    if ($id > 0) {
        $db->query("UPDATE streaming_guides SET match_id = :match_id, country = :country, broadcaster_id = :broadcaster_id, ott_id = :ott_id WHERE id = :id");
        $db->bind(':id', $id);
    } else {
        $db->query("INSERT INTO streaming_guides (match_id, country, broadcaster_id, ott_id) VALUES (:match_id, :country, :broadcaster_id, :ott_id)");
    }
    $db->bind(':match_id', $match_id);
    $db->bind(':country', $country);
    $db->bind(':broadcaster_id', $broadcaster_id ?: null);
    $db->bind(':ott_id', $ott_id ?: null);
    $db->execute();
}
$guides = $db->query("SELECT sg.*, m.tournament_name, t1.name_en as home, t2.name_en as away, b.name as b_name, o.name as o_name FROM streaming_guides sg JOIN matches m ON sg.match_id = m.id JOIN teams t1 ON m.team_home_id = t1.id JOIN teams t2 ON m.team_away_id = t2.id LEFT JOIN broadcasters b ON sg.broadcaster_id = b.id LEFT JOIN ott_platforms o ON sg.ott_id = o.id");
$guides = $db->resultSet();
$matches = $db->query("SELECT m.id, m.tournament_name, t1.name_en as home, t2.name_en as away FROM matches m JOIN teams t1 ON m.team_home_id = t1.id JOIN teams t2 ON m.team_away_id = t2.id");
$matches = $db->resultSet();
$broadcasters = $db->query("SELECT id, name FROM broadcasters");
$broadcasters = $db->resultSet();
$otts = $db->query("SELECT id, name FROM ott_platforms");
$otts = $db->resultSet();

$page_title = "Streaming Guides";
$active_page = "streaming";
require_once __DIR__ . '/includes/header.php';
?>
<div class="mb-3">
    <a href="broadcasters.php" class="btn btn-outline-primary">Manage Broadcasters</a>
    <a href="ott.php" class="btn btn-outline-primary">Manage OTT Platforms</a>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="card p-3">
            <form method="POST"> <?php csrf_field(); ?>
                <input type="hidden" name="id" value="0">
                <div class="mb-3"><label>Match</label><select name="match_id" class="form-select"><?php foreach($matches as $m) echo "<option value='{$m['id']}'>{$m['tournament_name']}: {$m['home']} vs {$m['away']}</option>"; ?></select></div>
                <div class="mb-3"><label>Country</label><input type="text" name="country" class="form-control" required></div>
                <div class="mb-3"><label>Broadcaster</label><select name="broadcaster_id" class="form-select"><option value="0">None</option><?php foreach($broadcasters as $b) echo "<option value='{$b['id']}'>{$b['name']}</option>"; ?></select></div>
                <div class="mb-3"><label>OTT</label><select name="ott_id" class="form-select"><option value="0">None</option><?php foreach($otts as $o) echo "<option value='{$o['id']}'>{$o['name']}</option>"; ?></select></div>
                <button type="submit" name="save_guide" class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>
    <div class="col-md-8">
        <table class="table bg-white"><thead><tr><th>Match</th><th>Country</th><th>Broadcaster</th><th>OTT</th></tr></thead>
        <tbody><?php foreach($guides as $g) echo "<tr><td>{$g['home']} vs {$g['away']}</td><td>{$g['country']}</td><td>{$g['b_name']}</td><td>{$g['o_name']}</td></tr>"; ?></tbody>
        </table>
    </div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
