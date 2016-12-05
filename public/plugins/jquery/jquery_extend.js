$.extend(String.prototype, {
    htmlEncode: function () {
        return $("<div/>").text(this.toString()).html();
    },
    htmlDecode: function () {
        return $("<div/>").html(this.toString()).text();
    },
    nl2br: function () {
        return this.toString().replace(/\n/ig, "<br/>");
    },
    sp2nb: function () {
        return this.toString().replace(/ /ig, "&nbsp;");
    },
    padLeft: function (length, sign) {
        if (this.length >= length)
            return this.toString();
        else
            return (sign + this).padLeft(length, sign);
    },
    padRight: function (length, sign) {
        if (this.length >= length)
            return this.toString();
        else
            return (this + sign).padRight(length, sign);
    }
});



