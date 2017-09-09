<?php
/**
 * Created by PhpStorm.
 * User: Fabian
 * Date: 03.09.2017
 * Time: 07:06
 */

namespace Brotzka\LaravelHelper\Helpers;

use Illuminate\Support\Facades\DB;

/**
 * Class SlugHelper
 * Can be used to create a unique slug for a specific model.
 * Can ignore one model.
 * @package Brotzka\LaravelHelper\Helpers
 */
class SlugHelper
{
    public $table;
    public $mustBeUnique = true;
    public $connection = 'mysql';
    public $key = 'id';

    /**
     * SlugHelper constructor.
     * @param $table
     * @param string $connection
     */
    public function __construct($table, $connection = 'mysql')
    {
        $this->table = $table;
        $this->connection = $connection;

        $this->checkConnection();
    }

    /**
     * Checks, if the given connection is working.
     * @throws \Exception
     */
    private function checkConnection()
    {
        try {
            DB::connection($this->connection)->getPDO();
        } catch (\Exception $ex) {
            throw new \Exception(trans('laravel-helper::messages.no-database-connection'));
        }
    }

    /**
     * Creates a unique slug.
     * @param $title
     * @param $idOfIgnoredModel
     * @return string
     */
    public function createUniqueSlug($title, $idOfIgnoredModel = NULL)
    {
        $i = 0;
        $slugIsUnique = false;
        while (!$slugIsUnique) {

            $slug = $this->createSlug($title, $i);

            $results = $this->getResultsCount($slug, $idOfIgnoredModel);

            if ($results == 0) {
                $slugIsUnique = true;
            }
            $i++;
        }
        return $slug;
    }

    /**
     * Returns the number of results for a given slug.
     * @param $slug
     * @param null $idOfIgnoredModel
     * @return mixed
     */
    private function getResultsCount($slug, $idOfIgnoredModel = NULL)
    {
        if (is_null($idOfIgnoredModel)) {
            return DB::connection($this->connection)
                ->table($this->table)
                ->where('slug', $slug)
                ->count();
        } else {
            return DB::connection($this->connection)
                ->table($this->table)
                ->where([
                    ['slug', '=', $slug],
                    ['id', '!=', $idOfIgnoredModel]
                ])
                ->count();
        }
    }

    /**
     * Creates a slug from the given title. If needed extended with a version.
     * @param $title
     * @param int $version
     * @return string
     */
    private function createSlug($title, $version = 0)
    {
        if ($version == 0) {
            return str_slug($title);
        } else {
            return str_slug($title) . '-' . $version;
        }
    }
}