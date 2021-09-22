from flask import Flask, request
import socket

dev = socket.gethostbyname("website")
app = Flask(__name__)

@app.route('/flag')
def hello_world():
    if request.remote_addr == dev and 'iPhone' not in request.headers.get('User-Agent'):
        fp = open('/flag', 'r')
        flag = fp.read()
        return flag
    else:
        return "Nope.."

@app.route('/')
def main():
    return 'This server is for you to get the flag!'

if __name__ == "__main__":
    app.run(host='0.0.0.0', debug=False, port=80)
