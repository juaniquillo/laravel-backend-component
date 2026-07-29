<?php

declare(strict_types=1);

namespace Juaniquillo\BackendComponents\Enums;

enum ComponentEnum: string
{
    case TEMPLATE = 'template';

    /**
     * A component collection
     * without a wrapper.
     */
    case COLLECTION = 'collection';

    /**
     * Block
     */
    case DIV = 'div';
    case PARAGRAPH = 'paragraph';

    /**
     * inline
     */
    case BUTTON = 'inline.button';
    case LINK = 'inline.link';
    case IMG = 'inline.img';
    case SPAN = 'inline.span';
    case BOLD = 'inline.bold';
    case EM = 'inline.em';
    case ITALIC = 'inline.italic';
    case STRONG = 'inline.strong';
    case SMALL = 'inline.small';

    /**
     * Headers
     */
    case H1 = 'headers.h1';
    case H2 = 'headers.h2';
    case H3 = 'headers.h3';
    case H4 = 'headers.h4';
    case H5 = 'headers.h5';
    case H6 = 'headers.h6';

    /**
     * Form
     */
    case FORM = 'form.form';
    case LABEL = 'form.label';
    case LEGEND = 'form.legend';
    case FIELDSET = 'form.fieldset';
    case TEXT_INPUT = 'form.text';
    case FILE_INPUT = 'form.file';
    case EMAIL_INPUT = 'form.email';
    case SEARCH_INPUT = 'form.search';
    case PASSWORD_INPUT = 'form.password';
    case CHECKBOX_INPUT = 'form.checkbox';
    case HIDDEN_INPUT = 'form.hidden';
    case RADIO_INPUT = 'form.radio';
    case DATALIST = 'form.datalist';
    case TEXTAREA = 'form.textarea';
    case SELECT = 'form.select';
    case OPTGROUP = 'form.optgroup';
    case OPTION = 'form.option';

    /**
     * Table
     */
    case TABLE = 'table.table';
    case THEAD = 'table.thead';
    case TBODY = 'table.tbody';
    case TFOOT = 'table.tfoot';
    case TR = 'table.tr';
    case TH = 'table.th';
    case TD = 'table.td';
    case CAPTION = 'table.caption';
    case COLGROUP = 'table.colgroup';
    case COL = 'table.col';

    /**
     * Lists
     */
    case OL = 'lists.ol';
    case UL = 'lists.ul';
    case LI = 'lists.li';

    /**
     * Details
     */
    case DETAILS = 'details.details';
    case SUMMARY = 'details.summary';

    /**
     * Layers
     */
    case DIALOG = 'layers.dialog';

}
