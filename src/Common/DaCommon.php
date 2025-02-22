<?php


namespace NFePHP\DA\Common;

use NFePHP\DA\Legacy\Common;

class DaCommon extends Common
{
    protected $debugmode;
    protected $orientacao = 'P';
    protected $papel = 'A4';
    protected $margsup = 2;
    protected $margesq = 2;
    protected $wCanhoto = 2;
    protected $wPrint;
    protected $hPrint;
    protected $xIni;
    protected $yIni;
    protected $maxH;
    protected $maxW;
    protected $fontePadrao = 'times';
    protected $aFont = ['font' => 'times', 'size' => 8, 'style' => ''];
    protected $creditos;
    
    /**
     * Ativa ou desativa o modo debug
     *
     * @param bool $activate Ativa ou desativa o modo debug
     *
     * @return bool
     */
    public function debugMode($activate = null)
    {
        if (isset($activate) && is_bool($activate)) {
            $this->debugmode = $activate;
        }
        if ($this->debugmode) {
            //ativar modo debug
            error_reporting(E_ALL);
            ini_set('display_errors', 'On');
        } else {
            //desativar modo debug
            error_reporting(0);
            ini_set('display_errors', 'Off');
        }
        return $this->debugmode;
    }
    
        /**
     * Renderiza o pdf e retorna como raw
     *
     * @return string
     */
    public function render()
    {
        if (empty($this->pdf)) {
            $this->monta();
        }
        if (!$this->debugmode) {
            return $this->pdf->getPdf();
        }
        echo "Modo Debug Ativado";
    }

    
    /**
     * Add the credits to the integrator in the footer message
     *
     * @param string $message Mensagem do integrador a ser impressa no rodapé das paginas
     *
     * @return void
     */
    public function creditsIntegratorFooter($message = '')
    {
        $this->creditos = trim($message);
    }
    
    /**
     *
     * @param string $font
     */
    public function setFontType(string $font = 'times')
    {
        $this->aFont['font'] = $font;
    }
    
    /**
     * Seta as margens superior e esquerda
     * a margem direita é igual a esquerda e
     * a inferior é igual a superior
     * @param int $margSup
     * @param int $margEsq
     *
     * @return void
     */
    public function margins(int $margSup = null, int $margEsq = null)
    {
        $this->margsup = $margSup ?? 2;
        $this->margesq = $margEsq ?? 2;
    }
    
    /**
     * Seta o tamanho da fonte
     *
     * @param int $size
     *
     * @return void
     */
    protected function setFontSize(int $size = 8)
    {
        
        $this->aFont['size'] = $size;
    }
    
    /**
     *
     * @param string $style
     */
    protected function setFontStyle(string $style = '')
    {
        $this->aFont['style'] = $style;
    }
    
    /**
     * Seta a orientação
     *
     * @param string $force
     * @param string $tpImp
     *
     * @return void
     */
    protected function setOrientationAndSize(
        $force = null,
        $tpImp = null
    ) {
        if (!empty($force) && ($force === 'P' || $force === 'L')) {
            $this->orientacao = $force;
        } elseif (!empty($tpImp)) {
            if ($tpImp == '1') {
                $this->orientacao = 'P';
            } else {
                $this->orientacao = 'L';
            }
        } else {
            $this->orientacao = 'P';
        }
        if ($this->orientacao == 'P') {
            if (strtoupper($this->papel) == 'A4') {
                $this->maxW = 210;
                $this->maxH = 297;
            } else {
                $this->papel = 'legal';
                $this->maxW = 216;
                $this->maxH = 355;
            }
        } else {
            if (strtoupper($this->papel) == 'A4') {
                $this->maxH = 210;
                $this->maxW = 297;
            } else {
                $this->papel = 'legal';
                $this->maxH = 216;
                $this->maxW = 355;
            }
        }
        $this->wPrint = $this->maxW - ($this->margesq * 2);
        $this->hPrint = $this->maxH - $this->margsup - 2;
    }
}
