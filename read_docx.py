import zipfile
import re
import sys
import xml.etree.ElementTree as ET

def extract_text_from_xml(xml_content):
    try:
        root = ET.fromstring(xml_content)
        # Find all <w:t> tags
        namespaces = {'w': 'http://schemas.openxmlformats.org/wordprocessingml/2006/main'}
        texts = root.findall('.//w:t', namespaces)
        return ''.join([t.text for t in texts if t.text])
    except Exception as e:
        return str(e)

def parse_docx(filepath):
    try:
        with zipfile.ZipFile(filepath, 'r') as docx:
            files = docx.namelist()
            print("=== DOCX CONTENTS ===")
            
            headers = [f for f in files if f.startswith('word/header')]
            footers = [f for f in files if f.startswith('word/footer')]
            
            for h in headers:
                print(f"\\n--- {h} ---")
                xml_content = docx.read(h)
                print(extract_text_from_xml(xml_content))
                
            for f in footers:
                print(f"\\n--- {f} ---")
                xml_content = docx.read(f)
                print(extract_text_from_xml(xml_content))
                
            print(f"\\n--- word/document.xml ---")
            if 'word/document.xml' in files:
                doc_xml = docx.read('word/document.xml')
                print(extract_text_from_xml(doc_xml))
                
    except Exception as e:
        print("Error reading docx:", e)

if __name__ == '__main__':
    parse_docx('AI_LH_FY26-27.docx')
