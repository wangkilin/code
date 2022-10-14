
<script type="text/javascript">
function compute_chars_ammount ()
{
    // =((H5+I5)*J5+K5*L5+M5*N5+O5*P5+Q5*R5+S5*T5+U5*V5)*W5
    var totalChars =
        ( (float($('#workload-edit-form-container input[name="content_table_pages"]').val())
                    + float($('#workload-edit-form-container input[name="text_pages"]').val() ) )
                * float($('#workload-edit-form-container input[name="text_table_chars_per_page"]').val() )
            + (float($('#workload-edit-form-container input[name="answer_pages"]').val() )
                * float($('#workload-edit-form-container input[name="answer_chars_per_page"]').val() ) )
            + (float($('#workload-edit-form-container input[name="test_pages"]').val())
                * float($('#workload-edit-form-container input[name="test_chars_per_page"]').val())  )
            + (float($('#workload-edit-form-container input[name="test_answer_pages"]').val())
                * float($('#workload-edit-form-container input[name="test_answer_chars_per_page"]').val())  )
            + (float($('#workload-edit-form-container input[name="exercise_pages"]').val())
                * float($('#workload-edit-form-container input[name="exercise_chars_per_page"]').val())  )
            + (float($('#workload-edit-form-container input[name="function_book"]').val())
                * float($('#workload-edit-form-container input[name="function_book_chars_per_page"]').val())  )
            + (float($('#workload-edit-form-container input[name="function_answer"]').val())
                * float($('#workload-edit-form-container input[name="function_answer_chars_per_page"]').val())  )
        )
        * float($('#workload-edit-form-container input[name="weight"]').val() ) ;
    totalChars = float(totalChars, 4);
    var amount = float(totalChars * 2, 2);

    console && console.info( float($('#workload-edit-form-container input[name="content_table_pages"]').val())
                    , float($('#workload-edit-form-container input[name="text_pages"]').val() )
                , float($('#workload-edit-form-container input[name="text_table_chars_per_page"]').val() )
            , (float($('#workload-edit-form-container input[name="answer_pages"]').val() )
                * float($('#workload-edit-form-container input[name="answer_chars_per_page"]').val() ) )
            , (float($('#workload-edit-form-container input[name="test_pages"]').val())
                * float($('#workload-edit-form-container input[name="test_chars_per_page"]').val())  )
            , (float($('#workload-edit-form-container input[name="test_answer_pages"]').val())
                * float($('#workload-edit-form-container input[name="test_answer_chars_per_page"]').val())  )
            , (float($('#workload-edit-form-container input[name="exercise_pages"]').val())
                * float($('#workload-edit-form-container input[name="exercise_chars_per_page"]').val())  )
            , (float($('#workload-edit-form-container input[name="function_book"]').val())
                * float($('#workload-edit-form-container input[name="function_book_chars_per_page"]').val())  )
            , (float($('#workload-edit-form-container input[name="function_answer"]').val())
                * float($('#workload-edit-form-container input[name="function_answer_chars_per_page"]').val())  )

        , float($('#workload-edit-form-container input[name="weight"]').val() )
    );
   $('#workload-edit-form-container input[name="total_chars"]').val(totalChars);
   $('#workload-edit-form-container input[name="payable_amount"]').val(amount);
   //return {'chars':totalChars, 'amount':amount};
}
/**
 * 取消提交工作量， 将工作量填充表单移除
 */
function concel_form ()
{
    $('#workload-edit-form-container').remove();
    return false;
}

function submit_workload_edit_form ()
{
    // 检查输入参数， 是否比基准参数大。 如果比进准参数大，判断出有错误
    $inputs = $('#workload-edit-form input');
    $hasWarning = false;
    for (var i=0; i<$inputs.length; i++) {
        if ($inputs.eq(i).data('default')===undefined) { // 没有基准参数， 不需要比较
            continue;
        }
        // 比基准参数大， 标识错误。
        if (float($inputs.eq(i).val()) > float($inputs.eq(i).data('default'))) {
            $hasWarning = true;
            $inputs.eq(i).parent().addClass('has-error');
        }
    }
    // 标识了参数错误， 提醒是否确认提交
    if ($hasWarning) {
        var onYesCallback = function () {
            compute_chars_ammount();
            ICB.ajax.postForm($('#workload-edit-form'));
            $('#workload-edit-form-container').remove();
        };

        ICB.modal.confirm('存在不一致的参数。 是否继续提交？', onYesCallback);
    } else { // 没有错误， 直接提交
        compute_chars_ammount();
        ICB.ajax.postForm($('#workload-edit-form'));
        $('#workload-edit-form-container').remove();
    }


    return false;
}
</script>
