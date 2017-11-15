from hashlib import sha256
import string
import sys
import itertools

def find_mined(prefix, fileName):
	addition_size = 1
	while True:
		for x in itertools.permutations(string.ascii_letters, addition_size):
			add_last_line(fileName, ''.join(x))
			if check_hash(fileName, prefix):
				return
			del_last_line(fileName)
		addition_size += 1


def setup_mined(fileName):
	with open(fileName) as f:
		contents = f.read()
	fileName += ".mined"
	with open(fileName, "w") as f:
		f.write(contents)

def del_last_line(fileName):
	with open(fileName) as f:
		contents = f.readlines()

	with open(fileName, "w") as f:
		for line in contents[:-1]:
			f.write(line)

def add_last_line(fileName, contents):
	contents = "["+contents+"]"
	with open(fileName, "a") as f:
		f.write(contents+'\n')


def get_hash(fileName):
	# Returns sha256 string
	f = open(fileName, "rb")
	return sha256(f.read()).hexdigest()

def check_hash(fileName, prefix):
	return get_hash(fileName).startswith(prefix)


def main(prefix, fileName):
	setup_mined(fileName)
	find_mined(prefix, fileName+".mined")

if __name__ == "__main__":
	if len(sys.argv) == 3:
		main(sys.argv[1], sys.argv[2])
	#del_last_line("test.txt")
	
