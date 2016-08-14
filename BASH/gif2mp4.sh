#!/bin/bash

set -eu

if (( $# != 1 )); then
    echo "Converts ANIMATED-GIF to a video file."
    echo 1>&2
    echo "Usage: gif2mp4 ANIMATED-GIF" 1>&2

    exit 1
fi

INFILE="${1}"
OUTFILE="${INFILE%.gif}.mp4"
# Stick the .mp4 in the same directory as the original .gif, regardless of
# current directory
OUTFILE="$(dirname -- "${OUTFILE}")/$(basename -- "${OUTFILE}")"

# the scale bit is because x264 (yuv420p, to be exats) needs image dimensions divisible by 2
ffmpeg -i "${INFILE}" -c:v libx264 -an -movflags +faststart -vf 'scale=-2:ih' -pix_fmt yuv420p "${OUTFILE}"
