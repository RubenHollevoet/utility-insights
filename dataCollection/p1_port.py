#inspired by https://github.com/jensdepuydt/belgian_digitalmeter_p1
#see the following page for the matching codes: https://jensd.be/1183/linux/read-data-from-the-belgian-digital-meter-through-the-p1-port

import serial
import sys
import crcmod.predefined
import re

# Change your serial port here:
serialport = '/dev/ttyUSB0'

consumption = 0
production = 0
consumption_day = 0
production_day = 0
consumption_night = 0
production_night = 0

def checkcrc(p1telegram):
    # check CRC16 checksum of telegram and return False if not matching
    # split telegram in contents and CRC16 checksum (format:contents!crc)
    for match in re.compile(b'\r\n(?=!)').finditer(p1telegram):
        p1contents = p1telegram[:match.end() + 1]
        # CRC is in hex, so we need to make sure the format is correct
        givencrc = hex(int(p1telegram[match.end() + 1:].decode('ascii').strip(), 16))
    # calculate checksum of the contents
    calccrc = hex(crcmod.predefined.mkPredefinedCrcFun('crc16')(p1contents))
    # check if given and calculated match
    if givencrc != calccrc:
        return False
    return True


def parsetelegramline(p1line):
    global consumption, production, consumption_day, production_day, consumption_night, production_night

    if p1line.startswith('1-0:1.7.0'):
        consumption = p1line.split('(')[1].split('*')[0]

    if p1line.startswith('1-0:2.7.0'):
        production = p1line.split('(')[1].split('*')[0]

    if p1line.startswith('1-0:1.8.1'):
        consumption_day = p1line.split('(')[1].split('*')[0]

    if p1line.startswith('1-0:2.8.1'):
        production_day = p1line.split('(')[1].split('*')[0]

    if p1line.startswith('1-0:1.8.2'):
        consumption_night = p1line.split('(')[1].split('*')[0]

    if p1line.startswith('1-0:2.8.2'):
        production_night = p1line.split('(')[1].split('*')[0]

def readSerialData():
    ser = serial.Serial(serialport, 115200, xonxoff=1)
    p1telegram = bytearray()
    while True:
        try:
            # read input from serial port
            p1line = ser.readline()
            # P1 telegram starts with /
            # We need to create a new empty telegram
            if "/" in p1line.decode('ascii'):
                p1telegram = bytearray()
                print('*' * 60 + "\n")
            # add line to complete telegram
            p1telegram.extend(p1line)
            # P1 telegram ends with ! + CRC16 checksum
            if "!" in p1line.decode('ascii'):
                if checkcrc(p1telegram):
                    # parse telegram contents, line by line
                    output = []
                    for line in p1telegram.split(b'\r\n'):
                        r = parsetelegramline(line.decode('ascii'))
                        if r:
                            output.append(r)

                return {
                    'consumption_current': int(float(consumption) * 1000),
                    'consumption_day': consumption_day,
                    'consumption_night': consumption_night,
                    'production_current': int(float(production) * 1000),
                    'production_day': production_day,
                    'production_night': production_night
                }
        except:
            print ("Something went wrong...")
            ser.close()
        # flush the buffer
        ser.flush()

def main():
    readSerialData()

#uncomment to simply test the script
#main()