moehost
======

In order to play the challenge in your local, you must download this image and run with `qemu-system-x86_64`.
The entire sourcecode is not incldued in this directory.

[Download](https://drive.google.com/file/d/1y8crn0GrjUXC7A0AWm-9lhV-60k3Jab1/view?usp=sharing)

To run the challenge, please run the following command:

`qemu-system-x86_64 -snapshot -enable-kvm -nographic -hda moehost2.qcow2 -m 1024 -redir tcp:1234::1337 -netdev user,id=moeidc1,hostname=moehost1,net=10.0.2.0/24 -device e1000,netdev=moeidc1`

Then, in minutes you'll be able to access the challenge with `http://localhost:1234/`.
