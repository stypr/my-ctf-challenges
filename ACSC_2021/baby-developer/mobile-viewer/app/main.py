#!/usr/bin/python3 -u
# -*- coding: utf-8 -*-
# Developer: stypr (https://harold.kim/)

from flask import Flask, render_template, request, jsonify, abort
from flask_limiter import Limiter
from flask_limiter.util import get_remote_address

from uuid import uuid4
from urllib.parse import unquote_plus
from requests import get
from os import unlink

import time
import os
import re
import requests
import redis
import glob

app = Flask(__name__)
db = redis.Redis("redis")
limiter = Limiter(
    app,
    key_func=get_remote_address,
    default_limits=["5000 per day"]
)

@app.errorhandler(404)
def page_not_found(e):
    return render_template("404.html"), 404


@app.errorhandler(403)
def permission_denied(e):
    return render_template("403.html"), 403


@app.route("/", methods=["GET"])
def home():
    return render_template("home.html")


@app.route("/status", methods=["GET"])
def stats():
    return render_template("status.html")


@app.route("/api/export/jpg", methods=["POST"])
@limiter.limit("6/minute", override_defaults=False)
def export_png():
    url = request.get_json()["url"]

    # Flush screenshots
    dir_list = glob.glob("./static/output/*.jpg")
    print(dir_list)
    if len(dir_list) > 4096:
        print("[.] Flushing cache...")
        for _file in dir_list:
            try:
                os.remove(_file)
            except:
                pass

    # Check URL
    if url == "" or url == None:
        return jsonify({"result": False, "_id": ""})

    # Check if HTTP
    if re.match(r"(^https?://)", url) is None:
        return jsonify({"result": False, "_id": ""})

    # Filter some useless keywords
    ban_keywords = [
        "\r",
        "\n",
        "\t",
        "set",
        "stypr",  # Redis
        "file:",
        "data:",
        "gopher:",
        "ftp:",
        "ssh:",  # SSRF
        "chrome:", # Chrome
        "php",
        "html",
        "htm",
        "php3",
        "phps",
        "var",  # File Upload Injection
        "proc",
        "self",
        "cwd",
        "dev",  # LFI / RFI
    ]
    for i in ban_keywords:
        if i.lower() in url.lower():
            return jsonify({"result": False, "_id": ""})

    # Dummy check to ensure that the server actually exists
    try:
        output = get(url, timeout=2).text
    except:
        return jsonify({"result": False, "_id": ""})

    # Add the queue on redis.
    uuid = str(uuid4())
    _id = f"{uuid}/{url}"
    db.rpush("query", _id)
    result = {"result": True, "_id": uuid}
    return jsonify(result)


if __name__ == "__main__":
    app.run(host="0.0.0.0", port=80, debug=True)
