<?php

/**
 * Implement this interface to register a new field renderer mapping to either:
 * - Add the rendering of a new attribute type
 * - Overload the default rendering of an attribute type
 *
 * @since 3.1.0 N°6041
 *
 * @experimental Form / Field / Renderer should be used in more places in next iTop releases, which may introduce major API changes
 */
interface iFieldRendererMappingsExtension
{
    /**
     * @return array {
     *              array: {
     *                  field: string,
     *                  form_renderer: string,
     *                  field_renderer: string
     *              }
     *          }  List of field renderer mapping: FQCN field class, FQCN Form Renderer class, FQCN Field Renderer class
     *
     * Example:
     *
     * ```php
     * [
     *  ['field' => 'FQCN\FieldA', 'form_renderer' => 'Combodo\iTop\Renderer\Console\ConsoleFormRenderer', 'field_renderer' => 'FQCN\FieldRendererA'],
     *  ['field' => 'FQCN\FieldB', 'form_renderer' => 'Combodo\iTop\Renderer\Console\ConsoleFormRenderer', 'field_renderer' => 'FQCN\FieldRendererB'],
     *  ['field' => 'FQCN\FieldA', 'form_renderer' => 'Combodo\iTop\Renderer\Bootstrap\BsFormRenderer', 'field_renderer' => 'FQCN\FieldRendererA'],
     *  ['field' => 'FQCN\FieldB', 'form_renderer' => 'Combodo\iTop\Renderer\Bootstrap\BsFormRenderer', 'field_renderer' => 'FQCN\FieldRendererB'],
     * ]
     * ```
     */
    public static function RegisterSupportedFields(): array;
}