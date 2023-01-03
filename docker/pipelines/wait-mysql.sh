#!/bin/bash

statusFile=/tmp/mysql-status
while [[ true ]]; do
  telnet 127.0.0.1 3306 &> ${statusFile}
  status=$(grep "Connection refused" ${statusFile} | wc -l)
  echo "Status: $status"

  if [[ "${status}" -eq 1 ]]; then
    echo "MySQL not running, waiting."
    sleep 1
  else
    rm ${statusFile}
    echo "MySQL running, ready to proceed."
    sleep 1
    break;
  fi
done

exit 0
