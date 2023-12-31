class AdminProductAttributes
  params:
    i18n:
      saved: ''
      removed: ''
      option_removed: ''
      confirm_remove: ''

  constructor: (@params) ->
    jQuery('#add-attribute').on 'click', @addAttribute
    jQuery('table#product-attributes > tbody')
      .on 'click', '.remove-attribute', @removeAttribute
      .on 'change', '.attribute input, .attribute select', @updateAttribute
      .on 'sortupdate', '.ui-sortable', @updateAttribute
      .on 'click', '.configure-attribute, .options button', @configureAttribute
      .on 'click', '.remove-attribute-option', @removeAttributeOption
      .on 'click', '.add-option', @addAttributeOption
      .on 'change', '.options tbody input', @updateAttributeOption
    @$newLabel = jQuery('#attribute-label')
    @$newSlug = jQuery('#attribute-slug')
    @$newType = jQuery('#attribute-type')

  addAttribute: (event) =>
    $container = jQuery('tbody', jQuery(event.target).closest('table'))
    jQuery.ajax
      url: jigoshop.getAjaxUrl()
      type: 'post'
      dataType: 'json'
      data:
        action: 'jigoshop.admin.product_attributes.save'
        label: @$newLabel.val()
        slug: @$newSlug.val()
        type: @$newType.val()
    .done (data) =>
      if data.success? and data.success
        @$newLabel.val('')
        @$newSlug.val('')
        @$newType.val('0').trigger('change')
        jQuery(data.html).appendTo($container)
      else
        jigoshop.addMessage('danger', data.error, 6000)
  updateAttribute: (event, ui) =>
    if event.type == 'sortupdate'
      $parent = jQuery(ui.item).parents('table').parents('tr').prevAll('tr.attribute:first')
    else
      $parent = jQuery(event.target).closest('tr')

    optionsOrder = []
    $parent.next('tr').find('tbody').find('tr').each (index, element) ->
      optionsOrder.push(jQuery(element).data('id'))

    jQuery.ajax
      url: jigoshop.getAjaxUrl()
      type: 'post'
      dataType: 'json'
      data:
        action: 'jigoshop.admin.product_attributes.save'
        id: $parent.data('id')
        label: jQuery('input.attribute-label', $parent).val()
        slug: jQuery('input.attribute-slug', $parent).val()
        type: jQuery('select.attribute-type', $parent).val()
        optionsOrder: optionsOrder
    .done (data) =>
      if data.success? and data.success
        $parent.replaceWith(data.html)
        jigoshop.addMessage('success', @params.i18n.saved, 2000)
      else
        jigoshop.addMessage('danger', data.error, 6000)
  removeAttribute: (event) =>
    if confirm(@params.i18n.confirm_remove)
      $parent = jQuery(event.target).closest('tr')
      jQuery.ajax
        url: jigoshop.getAjaxUrl()
        type: 'post'
        dataType: 'json'
        data:
          action: 'jigoshop.admin.product_attributes.remove'
          id: $parent.data('id')
      .done (data) =>
        if data.success? and data.success
          $parent.remove()
          jigoshop.addMessage('success', @params.i18n.removed, 2000)
        else
          jigoshop.addMessage('danger', data.error, 6000)
  configureAttribute: (event) ->
    $parent = jQuery(event.target).closest('tr')
    $options = jQuery('tr.options[data-id=' + $parent.data('id') + ']').toggle()
    $options.find('tbody').sortable()

  addAttributeOption: (event) ->
    $parent = jQuery(event.target).closest('tr.options')
    $container = jQuery('tbody', $parent)
    $label = jQuery('input.new-option-label', $parent)
    $value = jQuery('input.new-option-value', $parent)
    jQuery.ajax
      url: jigoshop.getAjaxUrl()
      type: 'post'
      dataType: 'json'
      data:
        action: 'jigoshop.admin.product_attributes.save_option'
        attribute_id: $parent.data('id')
        label: $label.val()
        value: $value.val()
    .done (data) ->
      if data.success? and data.success
        $label.val('')
        $value.val('')
        jQuery(data.html).appendTo($container)
      else
        jigoshop.addMessage('danger', data.error, 6000)
  updateAttributeOption: (event) =>
    $parent = jQuery(event.target).closest('tr')
    jQuery.ajax
      url: jigoshop.getAjaxUrl()
      type: 'post'
      dataType: 'json'
      data:
        action: 'jigoshop.admin.product_attributes.save_option'
        id: $parent.data('id')
        attribute_id: $parent.closest('tr.options').data('id')
        label: jQuery('input.option-label', $parent).val()
        value: jQuery('input.option-value', $parent).val()
    .done (data) =>
      if data.success? and data.success
        $parent.replaceWith(data.html)
        jigoshop.addMessage('success', @params.i18n.saved, 2000)
      else
        jigoshop.addMessage('danger', data.error, 6000)
  removeAttributeOption: (event) =>
    if confirm(@params.i18n.confirm_remove)
      $parent = jQuery(event.target).closest('tr')
      jQuery.ajax
        url: jigoshop.getAjaxUrl()
        type: 'post'
        dataType: 'json'
        data:
          action: 'jigoshop.admin.product_attributes.remove_option'
          id: $parent.data('id')
          attribute_id: $parent.closest('tr.options').data('id')
      .done (data) =>
        if data.success? and data.success
          $parent.remove()
          jigoshop.addMessage('success', @params.i18n.option_removed, 2000)
        else
          jigoshop.addMessage('danger', data.error, 6000)

jQuery ->
  new AdminProductAttributes(jigoshop_admin_product_attributes)
