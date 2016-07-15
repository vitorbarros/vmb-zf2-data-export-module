# vmb-zf2-data-export-module

## Instalação

### ZF2 Data Export Module

Rode
`php composer.phar vitorbarros/vmb-zf2-data-export-module`

Após, adicione os seguintes modulos  
`DoctrineModule`, `DoctrineORMModule`, `DoctrineDataFixtureModule` and `VMBDataExport` no seguinte arquivo: `config/application.config.php`

Após, crie o seguinte diertório com permissão de escrita
`data/DoctrineORMModule/Proxy`

Cria os seguintes diretórios

`public/xsl`

`public/pdf`

`public/csv`

### Export de dados através de entidades

```php
//controller class

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

//view
<?php $form = $this->form; ?>
<?php $form->setAttribute('action', '/export'); ?>
<?php $form->prepare(); ?>

<?php echo $this->form()->openTag($form); ?>

  <?php echo $this->formhidden($form->get('entity')); ?>
  <?php echo $this->formhidden($form->get('criteria')); ?>
  <?php echo $this->formhidden($form->get('type')); ?>
  <?php echo $this->formhidden($form->get('redirect_to')); ?>
  <?php echo $this->formhidden($form->get('headers')); ?>
  <?php echo $this->formsubmit($form->get('submit'); ?>

<?php echo $this->form()->closeTag(); ?>

```

### Export de dados com query customizada

```php
//controller class

use Zend\Mvc\Controller\AbstractActionController;
use VMBDataExport\Service\CustomExportService;

class YourController extends AbstractCrudController
{

  /**
   * @var CustomExportService
   */
  private $customExportService;

  /**
   * ExportController constructor.
   * @param CustomExportService $customExportService
   */
  public function __construct(CustomExportService $customExportService)
  {
      $this->customExportService = $customExportService;
  }

  public function yourAction()
  {
    $sql = "select
              ev.event_data_inicio as data_evento,
              en.end_cidade as cidade,
              en.end_estado as estado,
              COUNT(p.part_id) as participante,
              us.user_nome as treinadora,
              ca.cat_nome as modulo
            from EventosRest\Entity\Participante as p
            INNER JOIN Evento\Entity\Evento as ev
            WITH p.evento_event_id = ev.event_id
            INNER JOIN Evento\Entity\Endereco as en
            WITH ev.endereco_end_id = en.end_id
            INNER JOIN Evento\Entity\Categoria as ca
            WITH ev.categoria_cat_id = ca.cat_id
            INNER JOIN Evento\Entity\User as u 
            WITH u.user_id = p.user_user_id
            INNER JOIN Evento\Entity\Palestrante as pa
            WITH pa.evento_event_id = p.evento_event_id
            INNER JOIN Evento\Entity\User as us
            WITH pa.user_user_id = us.user_id
            GROUP BY cidade";

    $filePath = $this->customExportService->export($sql, array(
        'data_evento',
        'cidade',
        'estado',
        'participante',
        'treinadora',
        'modulo'
    ), 'xls');

    return $this->redirect()->toUrl($filePath);
  }
}

```
#### Acesse o navegador
`yourdomain.com.br/your-export-action`







