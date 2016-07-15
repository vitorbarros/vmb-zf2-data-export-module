# vmb-zf2-data-export-module

## Instalação

# ZF2 Data Export Module

Rode
`php composer.phar vitorbarros/vmb-zf2-data-export-module`

Após, adicione os seguintes modulos  
`DoctrineModule`, `DoctrineORMModule`, `DoctrineDataFixtureModule` and `VMBDataExport` no seguinte arquivo: `config/application.config.php`

Após, crie o seguinte diertório com permissão de escrita
`data/DoctrineORMModule/Proxy`

## Export de dados através de entidades

```php
use Zend\Mvc\Controller\AbstractActionController;
use VMBDataExport\Form\Export;

class YourController extends AbstractCrudController
{
  public function yourAction()
  {
    $form = new Export();
    $form->setData(array(
        'entity' => 'Your\Entity\NameSpace',
        //Condições da query
        'criteria' => Json::encode(array()),
        //Tipos suportados xls, pdf, csv
        'type' => 'xls',
        //url to redirect
        'redirect_to' => '/interno/super-admin/cursos',
        //headers
        'headers' => Json::encode(array(
            'event_nome',
            'event_data_inicio',
            'event_data_final',
            'event_descricao',
        )),
    ));
    
    return new ViewModel(
        array(
            'form' => $form,
        )
    );
  }
}
```
