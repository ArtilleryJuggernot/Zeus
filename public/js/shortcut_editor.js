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

    if (event.ctrlKey && event.keyCode === 66) {
        event.preventDefault();
        wrapSelectedText(textarea, '**', '**');
    }

    if(event.ctrlKey && event.keyCode === 73){
        event.preventDefault();
        wrapSelectedText(textarea, '*', '*');
    }

}

document.addEventListener('keydown', handleKeyPress);
