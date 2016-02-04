<%
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.1.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
use Cake\Utility\Inflector;

$fields = collection($fields)
    ->filter(function($field) use ($schema) {
        return $schema->columnType($field) !== 'binary';
    });

if (isset($modelObject) && $modelObject->behaviors()->has('Tree')) {
    $fields = $fields->reject(function ($field) {
        return $field === 'lft' || $field === 'rght';
    });
}
%>
<div class="ui grid">
    <div class="four wide column">
        <?= $this->SemanticMenu->render($TreeMenu); ?>
    </div>
    <div class="twelve wide column <%= $pluralVar %>">
        <h1 class="ui header"><?php echo $title_for_layout; ?></h1>

        <?= $this->Flash->render() ?>
            <?= $this->Form->create($<%= $singularVar %>) ?>
                
                    <h4 class="ui dividing header"><?= __('<%= Inflector::humanize($action) %> <%= $singularHumanName %>') ?></h4>
                    <?php
            <%
                    foreach ($fields as $field) {
                        if (in_array($field, $primaryKey)) {
                            continue;
                        }
                        if (isset($keyFields[$field])) {
                            $fieldData = $schema->column($field);
                            if (!empty($fieldData['null'])) {
            %>
                        echo $this->Form->input('<%= $field %>', ['options' => $<%= $keyFields[$field] %>, 'empty' => true]);
            <%
                            } else {
            %>
                        echo $this->Form->input('<%= $field %>', ['options' => $<%= $keyFields[$field] %>]);
            <%
                            }
                            continue;
                        }
                        if (!in_array($field, ['created', 'modified', 'updated'])) {
                            $fieldData = $schema->column($field);
                            if (($fieldData['type'] === 'date') && (!empty($fieldData['null']))) {
            %>
                        echo $this->Form->input('<%= $field %>', ['empty' => true]);
            <%
                            } else {
            %>
                        echo $this->Form->input('<%= $field %>');
            <%
                            }
                        }
                    }
                    if (!empty($associations['BelongsToMany'])) {
                        foreach ($associations['BelongsToMany'] as $assocName => $assocData) {
            %>
                        echo $this->Form->input('<%= $assocData['property'] %>._ids', ['options' => $<%= $assocData['variable'] %>]);
            <%
                        }
                    }
            %>
                    ?>
                
                <?= $this->Form->button(__('Submit')) ?>
                <?= $this->Form->end() ?>

    </div>
</div>