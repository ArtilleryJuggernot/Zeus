function wrapSelectedText(textarea, prefix, suffix) {
    if (textarea.selectionStart !== undefined) {
        var startPos = textarea.selectionStart;
        var endPos = textarea.selectionEnd;

        var text = textarea.value;

        var selectedText = text.substring(startPos, endPos);
        var newText = text.substring(0, startPos) + prefix + selectedText + suffix + text.substring(endPos);

        textarea.value = newText;

        textarea.setSelectionRange(startPos + prefix.length, endPos + prefix.length);
    }
}

function handleKeyPress(event) {
    var textarea = event.target;



    if (event.ctrlKey && !event.shiftKey) {
        switch (event.code) {
            case 'KeyB':
                event.preventDefault();
                wrapSelectedText(textarea, '**', '**');
                break;
            case 'KeyI':
                event.preventDefault();
                wrapSelectedText(textarea, '*', '*');
                break;
        }
    }



}

document.addEventListener('keydown', handleKeyPress);
