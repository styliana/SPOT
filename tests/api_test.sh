URL="http://web"

echo "--- Startowanie prostych testów integracyjnych ---"

HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" -L $URL/login)

if [ "$HTTP_CODE" -eq 200 ]; then
    echo "[PASS] Strona logowania dostępna (HTTP 200)"
else
    echo "[FAIL] Strona logowania zwróciła kod: $HTTP_CODE (Oczekiwano 200)"
fi



HTTP_CODE_404=$(curl -s -o /dev/null -w "%{http_code}" $URL/nieistnieje)

if [ "$HTTP_CODE_404" -eq 404 ]; then
    echo "[PASS] Obsługa błędu 404 działa poprawnie"
else
    echo "[FAIL] Oczekiwano 404, otrzymano: $HTTP_CODE_404"
fi

echo "--- Koniec testów ---"