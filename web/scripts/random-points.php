<?php

use Sensors\Application\DataObject\UtcDateTime;

require __DIR__ . '/../module/Application/src/DataObject/UtcDateTime.php';

/**
 * @noinspection start
 * Copied from https://gist.github.com/dazld/2173820
 */
class Perlin {

    var $p, $permutation, $seed;
    var $_default_size = 64;

    function Perlin ($seed=NULL) {

        //Initialize the permutation array.
        $this->p = array();
        $this->permutation = array( 151,160,137,91,90,15,
            131,13,201,95,96,53,194,233,7,225,140,36,103,30,69,142,8,99,37,240,21,10,23,
            190, 6,148,247,120,234,75,0,26,197,62,94,252,219,203,117,35,11,32,57,177,33,
            88,237,149,56,87,174,20,125,136,171,168, 68,175,74,165,71,134,139,48,27,166,
            77,146,158,231,83,111,229,122,60,211,133,230,220,105,92,41,55,46,245,40,244,
            102,143,54, 65,25,63,161, 1,216,80,73,209,76,132,187,208, 89,18,169,200,196,
            135,130,116,188,159,86,164,100,109,198,173,186, 3,64,52,217,226,250,124,123,
            5,202,38,147,118,126,255,82,85,212,207,206,59,227,47,16,58,17,182,189,28,42,
            223,183,170,213,119,248,152, 2,44,154,163, 70,221,153,101,155,167, 43,172,9,
            129,22,39,253, 19,98,108,110,79,113,224,232,178,185, 112,104,218,246,97,228,
            251,34,242,193,238,210,144,12,191,179,162,241, 81,51,145,235,249,14,239,107,
            49,192,214, 31,181,199,106,157,184, 84,204,176,115,121,50,45,127, 4,150,254,
            138,236,205,93,222,114,67,29,24,72,243,141,128,195,78,66,215,61,156,180
        );

        //Populate it
        for ($i=0; $i < 256 ; $i++) {
            $this->p[256+$i] = $this->p[$i] = $this->permutation[$i];
        }

        //And set the seed
        if ($seed === NULL) $this->seed = time();
        else $this->seed = $seed;
    }

    function noise($x,$y,$z,$size=NULL) {

        if ($size == NULL) $size = $this->_default_size;

        //Set the initial value and initial size
        $value = 0.0; $initialSize = $size;

        //Add finer and finer hues of smoothed noise together
        while($size >= 1) {

            $value += $this->smoothNoise($x / $size, $y / $size, $z / $size) * $size;
            $size /= 2.0;

        }

        //Return the result over the initial size
        return $value / $initialSize;

    }

    //This function determines what cube the point passed resides in
    //and determines its value.
    function smoothNoise($x, $y, $z) {

        //Offset each coordinate by the seed value
        $x+=$this->seed; $y+=$this->seed; $z+=$this->seed;

        $orig_x = $x;
        $orig_y = $y;
        $orig_z = $z;

        $X1 = (int)floor($x) & 255;                  // FIND UNIT CUBE THAT
        $Y1 = (int)floor($y) & 255;                  // CONTAINS POINT.
        $Z1 = (int)floor($z) & 255;
        $x -= floor($x);                                // FIND RELATIVE X,Y,Z
        $y -= floor($y);                                // OF POINT IN CUBE.
        $z -= floor($z);
        $u = $this->fade($x);                                // COMPUTE FADE CURVES
        $v = $this->fade($y);                                // FOR EACH OF X,Y,Z.
        $w = $this->fade($z);

        $A  = $this->p[$X1]+$Y1;
        $AA = $this->p[$A]+$Z1;
        $AB = $this->p[$A+1]+$Z1;      // HASH COORDINATES OF
        $B  = $this->p[$X1+1]+$Y1;
        $BA = $this->p[$B]+$Z1;
        $BB = $this->p[$B+1]+$Z1;      // THE 8 CUBE CORNERS,

        //Interpolate between the 8 points determined
        $result = $this->lerp($w, $this->lerp($v, $this->lerp($u, $this->grad($this->p[$AA  ], $x  , $y  , $z   ),  // AND ADD
            $this->grad($this->p[$BA  ], $x-1, $y  , $z   )), // BLENDED
            $this->lerp($u, $this->grad($this->p[$AB  ], $x  , $y-1, $z   ),  // RESULTS
                $this->grad($this->p[$BB  ], $x-1, $y-1, $z   ))),// FROM  8
            $this->lerp($v, $this->lerp($u, $this->grad($this->p[$AA+1], $x  , $y  , $z-1 ),  // CORNERS
                $this->grad($this->p[$BA+1], $x-1, $y  , $z-1 )), // OF CUBE
                $this->lerp($u, $this->grad($this->p[$AB+1], $x  , $y-1, $z-1 ),
                    $this->grad($this->p[$BB+1], $x-1, $y-1, $z-1 ))));

        return $result;
    }

    function fade($t) {
        return $t * $t * $t * ( ( $t * ( ($t * 6) - 15) ) + 10);
    }

    function lerp($t, $a, $b) {
        //Make a weighted interpolaton between points
        return $a + $t * ($b - $a);
    }

    function grad($hash, $x, $y, $z) {
        $h = $hash & 15;                      // CONVERT LO 4 BITS OF HASH CODE
        $u = $h<8 ? $x : $y;                 // INTO 12 GRADIENT DIRECTIONS.
        $v = $h<4 ? $y : ($h==12||$h==14 ? $x : $z);

        return (($h&1) == 0 ? $u : -$u) + (($h&2) == 0 ? $v : -$v);
    }

    //This function I've added. It creates one dimension of noise.
    function random1D($x, $size=NULL) {

        if ($size === NULL) $size = $this->_default_size;

        $x += $this->seed;

        $value = 0.0; $initialSize = $size = 3;

        while($size >= 1){
            $value += $this->smoothNoise($x*3 / $size, 100 / $size, 100 / $size);
            $size--;
        }

        if ($value < -1) $value = -1;
        if ($value > 1) $value = 1;

        return $value;

    }

    //Same as random1D() only for 2 dimensions.
    function random2D($x,$y,$size=NULL) {

        if ($size === NULL) $size = $this->_default_size;

        $x += $this->seed;
        $y += $this->seed;

        $value = 0.0; $initialSize = $size = 3;

        while($size >= 1) {
            $value += $this->smoothNoise($x*3 / $size, $y*3 / $size, 100 / $size);
            $size--;
        }

        if ($value < -1) $value = -1;
        if ($value > 1) $value = 1;

        return $value;

    }
}

/** @noinspection end */

$runInContainer = strlen(gethostname()) === 12;
if (!$runInContainer) {
    for ($i = 3; $i >= 0; $i--) {
        echo "\rTruncating Table in " . $i . '!     Press ^C to cancel! ';
        sleep(1);
    }

    echo "\r" . str_repeat(' ', 50);
} else {
    $confirm = strtolower(readline('Table will be truncated. OK? (y/N) '));
    if ($confirm !== 'y') {
        echo 'Aborting!', PHP_EOL;
        exit;
    }
}

# region PDO
$config = [
    'host'     => $runInContainer ? 'db' : '127.0.0.1',
    'port'     => 3306,
    'database' => 'sensor',
    'auth'     => [
        'user'     => 'sensor',
        'password' => 'o3r94uztg'
    ]
];

$dsn = sprintf(
    'mysql:dbname=%s;host=%s;port=%s',
    $config['database'],
    $config['host'],
    $config['port'],
);
# endregion

$user     = $config['auth']['user'];
$password = $config['auth']['password'];
$pdo      = new PDO($dsn, $user, $password);

$pdo->exec('TRUNCATE measurements');

$query = <<<SQL
INSERT INTO measurements (`timestamp`, `color`, `humidity`, `temperature`, `air_pressure`)
VALUES (:point_timestamp, :color, :humidity, :temperature, :air_pressure)
SQL;

$statement = $pdo->prepare($query, []);

$noise  = new Perlin(time());
$amount = $argv[1] ?? 10;
$scale  = 35;

$amountLength = strlen((string)$amount);

$wait = ($argv[2] ?? null) === 'wait';

for ($i = 1; $i <= $amount; $i++) {
    $progress = (int)(($i / $amount) * 100);
    echo sprintf(
        "\r|%s%s| (%s/%d) ",
            str_repeat('=', $progress),
            str_repeat(' ', 100 - $progress),
            str_pad((string)$i, $amountLength, ' ', STR_PAD_LEFT),
            $amount,
    );

    $humidity    = ($noise->random2D($i / $scale, 1) + 1) / 2;
    $temperature = ($noise->random2D($i / $scale, 2) + 1) / 2;
    $airPressure = ($noise->random2D($i / $scale, 3) + 1) / 2;
    $timestamp   = UtcDateTime::create('now + ' . ($i * 2) . ' seconds')->format('Y-m-d H:i:s');

    if ($wait) {
        sleep(1);
        $timestamp = UtcDateTime::create()->format('Y-m-d H:i:s');
    }

    $statement->execute([
        'point_timestamp' => $timestamp,
        'color'           => 1,
        'humidity'        => $humidity * 100,
        'temperature'     => ($temperature * 40) - 10,
        'air_pressure'    => $airPressure * 1000,
    ]);
}

echo PHP_EOL;
