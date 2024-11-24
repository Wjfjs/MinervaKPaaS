import logging
from flask import Flask

app = Flask(__name__)

logging.basicConfig(filename='app.log', level=logging.DEBUG, format='%(asctime)s %(levelname)s:%(message)s')

@app.route('/hello')
def hello():
    app.logger.info('Hello route was accessed')
    return 'Hello World!'

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)

