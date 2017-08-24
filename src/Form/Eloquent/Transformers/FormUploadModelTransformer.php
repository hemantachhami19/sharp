<?php

namespace Code16\Sharp\Form\Eloquent\Transformers;

use Code16\Sharp\Utils\Transformers\SharpAttributeTransformer;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class FormUploadModelTransformer implements SharpAttributeTransformer
{

    /**
     * Transform a model attribute to array (json-able).
     *
     * @param $instance
     * @param string $attribute
     * @return mixed
     */
    function apply($instance, string $attribute)
    {
        if(!$instance->$attribute) {
            return null;
        }

        if($instance->$attribute() instanceof MorphMany) {
            // We are handling a list of uploads
            return $instance->$attribute->map(function($upload) {
                $array = $this->transformUpload($upload);

                $file = array_only($array, ["name", "thumbnail", "size"]);

                return [
                    "file" => sizeof($file) ? $file : null,
                ] + array_except($array, ["name", "thumbnail", "size"]);
            })->all();
        };

        return $this->transformUpload($instance->$attribute);
    }

    protected function transformUpload($upload)
    {
        return ($upload->file_name ? [
                "name" => $upload->file_name,
                "thumbnail" => $upload->thumbnail(null, 150),
                "size" => $upload->size,
            ] : [])
            + ($upload->custom_properties ?? [])
            + ["id" => $upload->id];
    }
}