<?php

namespace App\Service;

use DateTime;

/**
 * Class CsvService
 * @package App\Service
 */
class CsvService
{
    const ENABLE = 'Enable';
    const DISABLE = 'Disable';

    /**
     * Permet de transformer le ficher csv en array
     *
     * @param $cvsFile le fichier CSV
     * @return array|array[]
     * @throws \Exception
     */
    public function extractCSVData($cvsFile)
    {
            // en se basant sur le fait que chaque fichier csv aura la meme structure
            $data['title'] = $cvsFile[0];
            unset($cvsFile[0]);
            $data['title'];
            $data['rows'] = $cvsFile;

            //on combine le tableau pour remplace les clés par les titres
            foreach ($data['rows'] as $row) {
                $lists[] = array_combine($data['title'], array_values($row));
            }

            foreach($lists as $key => $list){
                $lists[$key]['created_at'] = $this->formateDate($list['created_at']);
                $lists[$key]['title'] = $this->formatTitle($list['title']);
                $lists[$key]['is_enabled'] = $this->trasnformIs_Enable($list['is_enabled']);
                $lists[$key]['price'] = $this->formatPrice($list['price']);
                $lists[$key]['slug'] = $this->slugify($list['title']);
                unset($lists[$key]['currency']);
            }
            return $lists;
    }

    /**
     * permet de formater la date
     *
     * @param string $createAt La  date de création
     *
     * @return string
     * @throws \Exception
     */
    private function formateDate(string $createAt)
    {
        $date = new DateTime($createAt);
        return $date->format('l\, d-M-Y H:i:s e');
    }

    /**
     * permet de prendre en compte les balises HTML
     *
     * @param $title
     */
    private function formatTitle($title)
    {
        return preg_replace('/\<(\s*)?br(\s*)?\/?\>/i', "\n", $title);
    }

    /**
     * permet d'afficher le libelle enable ou disable
     *
     * @param bool $isEnable est affiche ou pas
     * @return string
     */
    private function trasnformIs_Enable(bool $isEnable)
    {
        if ($isEnable){

            return self::ENABLE;
        }

        return self::DISABLE;
    }

    /**
     * permet de formater le prix
     *
     * @param string $price le prix du produit
     * @return string
     */
    private function formatPrice(string $price)
    {
       return number_format($price, 2, ',', null).'€';
    }

    /**
     * permet de 'slugifier' le titre
     *
     * @param string $title le titre à transformer
     *
     * @return string|string[]|null
     */
    private function slugify(string $title)
    {
        $replace =
            [
                '/[^a-zA-Z0-9\s]/' => "-",
                '/\s/'=>'_',
            ];

        return preg_replace(array_keys($replace), array_values($replace), strtolower(trim(strip_tags($title))));
    }
}
