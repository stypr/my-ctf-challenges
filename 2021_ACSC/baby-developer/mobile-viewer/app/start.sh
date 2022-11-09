#!/bin/sh

# Start
nohup /start.sh &
# Worker
cd /app/worker
python worker.py
