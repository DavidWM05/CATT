<?php
namespace Classes;

class Poblacion{
    private $optimos;
    private $noOptimos;
    private $efectividad;
    private $epoca;

    public function __construct($optimos,$noOptimos,$efectividad,$epoca){
        $this->optimos = $optimos;
        $this->noOptimos = $noOptimos;
        $this->efectividad = $efectividad;
        $this->epoca = $epoca;
    }

    public function setOptimos($parametro) { $this->optimos = $parametro; }
    public function getOptimos() { return $this->optimos; }

    public function setNoOptimos($parametro) { $this->noOptimos = $parametro; }
    public function getNoOptimos() { return $this->noOptimos; }

    public function setEfectividad($parametro) { $this->efectividad = $parametro; }
    public function getEfectividad() { return $this->efectividad; }

    public function setEpoca($parametro) { $this->epoca = $parametro; }
    public function getEpoca() { return $this->epoca; }

    public function imprimir(){

        echo '<p> Efectividad ['.number_format($this->efectividad,2).']% optimos: '.count($this->optimos).' no Optimos:'.count($this->noOptimos).'</p>';
        echo '<table>';
        echo "<tr>
                <td>Nombre</td>
                <td>Fecha</td>
                <td>Horario</td>
                <td>Salon</td>
                <td>CHOP</td>
                <td>DCT</td>
                <td>optimo</td>
                <td>Director 1</td>
                <td>Director 2</td>
                <td>Sinodal 1</td>
                <td>Sinodal 2</td>
                <td>Sinodal 3</td>
              </tr>";
        
        foreach ($this->optimos as $cromosoma){
            echo '<tr>';
                echo '<td>'.$cromosoma->getNombreTT().'</td>
                      <td>'.$cromosoma->getFecha().'</td>
                      <td>'.$cromosoma->getHorario().'</td>
                      <td>'.$cromosoma->getSalon().'</td>
                      <td>'.number_format($cromosoma->getEvaluacion_CHOP(),2).'</td>
                      <td>'.number_format($cromosoma->getEvaluacion_CDT(),2).'</td>
                      <td>'.$cromosoma->getOptimo().'</td>';
                $this->imprimirDocentes($cromosoma->getGrupoDocente());
            echo '</tr>';
        }
        foreach ($this->noOptimos as $cromosoma){
            echo '<tr>';
                echo '<td>'.$cromosoma->getNombreTT().'</td>
                      <td>'.$cromosoma->getFecha().'</td>
                      <td>'.$cromosoma->getHorario().'</td>
                      <td>'.$cromosoma->getSalon().'</td>
                      <td>'.number_format($cromosoma->getEvaluacion_CHOP(),2).'</td>
                      <td>'.number_format($cromosoma->getEvaluacion_CDT(),2).'</td>
                      <td>'.$cromosoma->getOptimo().'</td>';
                $this->imprimirDocentes($cromosoma->getGrupoDocente());
            echo '</tr>';
        }
        echo '</table>';
    }

    public function imprimirOptimos(){
        foreach ($this->optimos as $cromosoma) {
            echo "<tr>";
            echo    "<td>".$cromosoma->getHorario()."</td>";
            echo    "<td>".$cromosoma->getSalon()."</td>";
            echo    "<td>".$cromosoma->getNumeroTT()."</td>";
            echo    "<td>".$cromosoma->getTitulo()."</td>";
            $this->imprimirDocentes($cromosoma->getGrupoDocente());
            echo "</tr>";
        }
    }

    private function imprimirDocentes($parametro) {

        if(count($parametro) == 5){

            foreach ($parametro as $key => $value) {
                if($value == 'director'){
                    echo '<td>'.$key.'</td>';
                }
            }

            foreach ($parametro as $key => $value) {                
                if($value == 'sinodal'){
                    echo '<td>'.$key.'</td>';
                }
            }
        }else{
            foreach ($parametro as $key => $value) {
                if($value == 'director'){
                    echo '<td>'.$key.'</td>';
                }
            }

            echo '<td></td>';

            foreach ($parametro as $key => $value) {              
                if($value == 'sinodal'){
                    echo '<td>'.$key.'</td>';
                }
            }
        }
    }
}