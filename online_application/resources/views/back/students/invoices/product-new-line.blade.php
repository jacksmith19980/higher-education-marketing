@php
    if (isset($product)) {
        $category = array_slice(explode("\\", $product->invoiceable_type), -1)[0];
    }
@endphp
<tr>
    <td class="product-{{$order}}">
        @include('back.layouts.core.forms.select', [
            'name'          => 'educational_product',
            'label'         => '',
            'class'         => 'ajax-form-field new-single-item',
            'required'      => true,
            'attr'          => 'onchange=app.amountByProduct(this) data-order=' . $order,
            'placeholder'   => 'Select a Product',
            'data'          => $products,
            'value'         => $product->id,
            'sharedIds'     => true
        ])
        </div>
    </td>

    <td>
        @include('back.layouts.core.forms.text-input', [
            'name'      => 'description',
            'label'     => '',
            'class'     => 'ajax-form-field description-' . $order,
            'required'  => true,
            'attr'      => '',
            'data'      => '',
            'value'     => isset($product) ? $product->propertie['description'] ?? null : ''
        ])
    </td>

    <td>
        @include('back.layouts.core.forms.text-input', [
            'name'      => 'amount',
            'label'     => '',
            'class'     => 'ajax-form-field amount-' . $order,
            'required'  => true,
            'attr'      => 'onkeyup=app.changeProductAmount()',
            'data'      => '',
            'value'     => isset($product) ? $product->amount : '0.00'
        ])
    </td>
    <td>

        @include('back.layouts.core.forms.checkbox', [
            'name' => 'taxable',
            'label' => false ,
            'class' => 'ajax-form-field' ,
            'required' => true,
            'attr' => '',
            'helper_text' => '',
            'value' => 0,
            'default' => 1,
            'helper' => ''
        ])
    </td>

    <td>
        <div class="btn-group more-optn-group">
            <button type="button"
               class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split ti-more-alt flat-btn"
               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            </button>
            <div class="dropdown-menu">
               <a href="javascript:void(0)"
                  onclick=""
                  class="dropdown-item" data-toggle="tooltip" data-placement="top"
                  data-original-title="{{__('View')}}">
                  <i class="icon-eye"></i><span class="pl-2 icon-text">{{__('View')}}</span>
               </a>
               <a href="javascript:void(0)"
                  onclick=""
                  class="dropdown-item" data-toggle="tooltip" data-placement="top"
                  data-original-title="{{__('Edit')}}">
                  <i class="icon-pencil"></i><span class="pl-2 icon-text">{{__('Edit')}}</span>
               </a>
               <a href="javascript:void(0)"
                  onclick="app.deleteProductFromInvoice(this)"
                  class="dropdown-item" data-toggle="tooltip" data-placement="top"
                  data-original-title="{{__('Delete')}}">
                  <i class="icon-trash text-danger"></i><span class="pl-2 icon-text text-danger">{{__('Delete')}}</span>
               </a>
            </div>
         </div>
    </td>
</tr>
