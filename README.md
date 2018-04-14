# Módulo para exportação de dados em CSV, XSL, e PDF
Based on [Módulo de exportação de dados de Vitor Monteiro de Barros](https://github.com/vitorbarros/vmb-zf2-data-export-module)

## Instalação

### Observação
Para que o módulo funcione de forma correta, é necessário seguir as instruções de instalação do módulo do Doctrine.
`https://github.com/doctrine/DoctrineORMModule`

### ZF2 Data Export Module

Rode
`php composer.phar require vitorbarros/vmb-zf2-data-export-module`

Após, adicione os seguintes modulos  
`DoctrineModule`, `DoctrineORMModule`, `DoctrineDataFixtureModule` and `VMBDataExport` no seguinte arquivo: `config/application.config.php`

Após, crie o seguinte diertório com permissão de escrita
`data/DoctrineORMModule/Proxy`

Cria os seguintes diretórios

`public/xsl`

`public/pdf`

`public/csv`

### Form ###
Your form can extend VMBDataExportForm Class like this:

```php
<?php
namespace MyModule\Form;

use Zend\Form\Form;

class FilterClaimForm extends VMBDataExportForm {

	public function __construct($name = null, $options = array())
	{
		parent::__construct('my-form');
		
		/**
		* All your controls here
		*/
	}
}
```

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
  <?php echo $this->formsubmit($form->get('submit')); ?>

<?php echo $this->form()->closeTag();

```

### Export Throw service injected in your Controller

```php

namespace MyNameSpace

use VMBDataExport\Service\MainService;
class MyController
{
	private $exportService;
	
	public function __contruct(MainService $exportService)
	{
		$this->exportService = $exportService;
	}
	
	public function CustomAction() {
		$filename = $this->exportService->strategy('xls', [
		'entity' => 'My\Entity\Name',
			'method' => 'filter', //You can call a custom Entity Repository Method
          'criteria' => Json::encode(
          	[$your_criteria_array]
			),
			'headers' => Json::encode([
				'field1',
             	'field2',
             	// ...
			]),
		]);
		
		return $this->redirect()->toUrl($filename);
	}
}
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
    $sql = "Your query";

    $filePath = $this->customExportService->export($sql, array(
        'your',
        'header',
        'fields'
    ), 'xls');

    return $this->redirect()->toUrl($filePath);
  }
}

```
#### Acesse o navegador
`yourdomain.com.br/your-export-action`

### TODO
Finalizar a classe `VMBDataExport\Export\PDFExport`




