<?php

namespace Combodo\iTop\Attachments\Enum;

enum AttachmentAjaxErrorEnum: int
{
	case MISSING_ARG_OBJ_CLASS = 1;
	case MISSING_ARG_TEMP_ID = 2;
	case UPLOAD_FILE_EMPTY = 10;
	case ACTION_NOT_ALLOWED = 20;
}
